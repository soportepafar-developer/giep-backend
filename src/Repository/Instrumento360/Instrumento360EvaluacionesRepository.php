<?php

namespace App\Repository\Instrumento360;

use App\Entity\Instrumento360\Instrumento360Evaluaciones;
use App\Entity\Instrumento360\Instrumento360UsuariosAsignados;
use App\Entity\Instrumento360\Competencia360;
use App\Entity\Instrumento360\NivelDominio;
use App\Entity\Proyecto\Empresa;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Helper\Helper;

/**
 * @extends ServiceEntityRepository<Instrumento360Evaluaciones>
 *
 * @method Instrumento360Evaluaciones|null find($id, $lockMode = null, $lockVersion = null)
 * @method Instrumento360Evaluaciones|null findOneBy(array $criteria, array $orderBy = null)
 * @method Instrumento360Evaluaciones[]    findAll()
 * @method Instrumento360Evaluaciones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Instrumento360EvaluacionesRepository extends ServiceEntityRepository
{
    private $security;
    private $validator;
    private $helper;

    public function __construct(ManagerRegistry $registry,Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Instrumento360Evaluaciones::class);
    }

    public function post($data,$validator,$helper): JsonResponse
    {
        $entityManager = $this->getEntityManager();
        $entity = $helper->setParametersToEntity(new Instrumento360Evaluaciones(), $data);
        
        $errors = $validator->validate($entity);
        if($errors->count() > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse(['msg' => $errorsString], 500);
        } else {
            $entityusuariosasignados =$entityManager->getRepository(Instrumento360UsuariosAsignados::class)->find($data['IdInstrumentoUsuario']);
            if (!$entityusuariosasignados) {
                return new JsonResponse(['msg'=>'No existe usuario asignado: '.$data['IdInstrumentoUsuario']],404);  
            }
            $entity->setIdInstrumentoUsuario($entityusuariosasignados);            

            $entitycomp =$entityManager->getRepository(competencia360::class)->find($data['idcompetencia360']);
            if (!$entitycomp) {
                return new JsonResponse(['msg'=>'No existe idcompetencia360: '.$data['idcompetencia360']],404);  
            }
            $entity->setCompetencia360($entitycomp);            

            $entityniveldominio =$entityManager->getRepository(NivelDominio::class)->find($data['idniveldominio']);
            if (!$entityniveldominio) {
                return new JsonResponse(['msg'=>'No existe nivel de dominio: '.$data['idniveldominio']],404);  
            }
            $entity->setNivelDominio($entityniveldominio);            

            $currentUser = $entityManager->getRepository(User::class)
                ->find($this->security->getUser()->getId());
            
            $entity->setCreateBy($currentUser->getUserName());
            $entity->setUpdateBy($currentUser->getUserName());
            
            $empresa = $entityManager->getRepository(Empresa::class)
                ->find($this->security->getUser()->getIdempresa());
            if($empresa) {
                $entity->setEmpresa($empresa);
            }

            $entityManager->persist($entity);
            $entityManager->flush();
            
            return new JsonResponse([
                'msg' => 'Registro Creado',
                'id' => $entity->getId()
            ], 200);
        }
    }

    public function save(Instrumento360Evaluaciones $evaluacion): void
    {
        $this->_em->persist($evaluacion);
        $this->_em->flush();
    }

    public function put($data, $id, $validator, $helper): JsonResponse
    {
        $entityManager = $this->getEntityManager();
        $entity = $this->find($id);

        if (!$entity) {
            return new JsonResponse(['msg' => 'No existe la evaluación con id: ' . $id], 404);
        }

        $entity = $helper->setParametersToEntity($entity, $data);
        
        $errors = $validator->validate($entity);
        if($errors->count() > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse(['msg' => $errorsString], 500);
        } else {
            // Actualizar campos si están presentes en los datos
            if (isset($data['IdInstrumentoUsuario'])) {
                $entityusuariosasignados = $entityManager->getRepository(Instrumento360UsuariosAsignados::class)->find($data['IdInstrumentoUsuario']);
                if (!$entityusuariosasignados) {
                    return new JsonResponse(['msg' => 'No existe usuario asignado: ' . $data['IdInstrumentoUsuario']], 404);
                }
                $entity->setIdInstrumentoUsuario($entityusuariosasignados);
            }

            if (isset($data['idcompetencia360'])) {
                $entitycomp = $entityManager->getRepository(competencia360::class)->find($data['idcompetencia360']);
                if (!$entitycomp) {
                    return new JsonResponse(['msg' => 'No existe competencia 360: ' . $data['idcompetencia360']], 404);
                }
                $entity->setCompetencia360($entitycomp);
            }

            if (isset($data['idniveldominio'])) {
                $entityniveldominio = $entityManager->getRepository(NivelDominio::class)->find($data['idniveldominio']);
                if (!$entityniveldominio) {
                    return new JsonResponse(['msg' => 'No existe nivel de dominio: ' . $data['idniveldominio']], 404);
                }
                $entity->setNivelDominio($entityniveldominio);
            }

            // Actualizar datos de auditoría
            $currentUser = $entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setUpdateBy($currentUser->getUserName());
            $entity->setUpdateAt(new \DateTime());

            $entityManager->flush();
            
            return new JsonResponse([
                'msg' => 'Registro Actualizado',
                'id' => $entity->getId()
            ], 200);
        }
    }

    public function get($id): JsonResponse
    {
        $entity = $this->find($id);

        if (!$entity) {
            return new JsonResponse(['msg' => 'No existe la evaluación con id: ' . $id], 404);
        }

        $evaluacionData = [
            'id' => $entity->getId(),
            'idinstrumentousuario' => $entity->getIdInstrumentoUsuario()->getId(),
            'idcompetencia360' => $entity->getCompetencia360()->getId(),
            'idniveldominio' => $entity->getNivelDominio()->getId(),
            'createBy' => $entity->getCreateBy(),
            'createAt' => $entity->getCreateAt() ? $entity->getCreateAt()->format('Y-m-d H:i:s') : null,
            'updateBy' => $entity->getUpdateBy(),
            'updateAt' => $entity->getUpdateAt() ? $entity->getUpdateAt()->format('Y-m-d H:i:s') : null
        ];

        return new JsonResponse($evaluacionData, 200);
    }

    public function delete($id, $validator, $helper): JsonResponse
    {
        $entityManager = $this->getEntityManager();
        $entity = $this->find($id);

        if (!$entity) {
            return new JsonResponse(['msg' => 'No existe la evaluación con id: ' . $id], 404);
        }

        try {
            $entityManager->remove($entity);
            $entityManager->flush();
            return new JsonResponse(['msg' => 'Registro Eliminado'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['msg' => 'Error al eliminar el registro'], 500);
        }
    }

    // /**
    //  * @return Instrumento360Evaluaciones[] Returns an array of Instrumento360Evaluaciones objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Instrumento360Evaluaciones
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
