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
        $entityManagerInstrumento = $this->getEntityManager();

        $entity = new Instrumento360Evaluaciones();
        $entity->setNombre(!is_null($data["name"])?$data["name"]:null);
        $entity->setDuration(!is_null($data["dutation"])?$data["dutation"]:null);
        $entity->setIdTipoUnidad(!is_null($data["unitType"])?$entityManager->getRepository(TipoUnidad::class)->find($data["unitType"]["id"]):null);
        $entity->setQuestionsByCategory(!is_null($data["questionsByCategory"])?$data["questionsByCategory"]:null);
        $entity->setPath(!is_null($data["path"])?$data["path"]:null);
        $entity->setFechaVigencia(!is_null($data["expirationDate"])?\DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime(str_replace('-','/', $data["expirationDate"] )))):null);
        $entity->setDescripcion(!is_null($data["description"])?$data["description"]:null);
        $entity->setPuntosGlobales(!is_null($data["puntosGlobales"])?$data["puntosGlobales"]:null);
        $entityStatus = $entityManager->getRepository(Status::class)->findOneById(1);          
        $entity->setStatusId($entityStatus); 
        $entity->setOrden(1);
        $entity->setPublicar(0);                
        if(isset($data["roles"])){
    
        } 
        $errors = $validator->validate($entity);
        if($errors->count() > 0){
            $errorsString = (string) $errors;
            return new JsonResponse(['msg'=>$errorsString],500);
        }else{
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa);

            $entity->setCreateBy($currentUser->getUserName());

            $entityManager->persist($entity);
            $entityManager->flush();

            if(isset($data["sections"])){
                foreach($data["sections"] as $valor){
                    $seccion = new Seccion();
                    $seccion->setInstrumento($entity);
                    $seccion->setNombre(!is_null($valor["name"])?$valor["name"]:null);
                    $seccion->setOrden(!is_null($valor["numberSection"])?$valor["numberSection"]:null);
                    $seccion->setUpdateAt(new \DateTime());
                    $seccion->setStatus($entityManager->getRepository(Status::class)->findOneById(1));          
                    $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                    $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                    if($empresa)
                      $seccion->setIdempresa($empresa);

                    $seccion->setUpdateBy($currentUser->getUserName());
                    $entityManager->persist($seccion);
                    $entityManager->flush();
                    foreach($valor["questions"] as $question){
                        $pregunta = new Pregunta();
                        $pregunta->setPregunta(!is_null($question["label"])?$question["label"]:null);
                        $pregunta->setOrden(!is_null($question["order"])?$question["order"]:null);
                        $pregunta->setIdInput(!is_null($question["inputType"])?$entityManager->getRepository(TipoInput::class)->find($question["inputType"]["id"]):null);
                        $pregunta->setObligatorio(!is_null($question["required"])?$question["required"]:null);
                        $pregunta->setPuntos(!is_null($question["score"])?$question["score"]:null);
                        $pregunta->setIdCategoria(!is_null($question["categoryId"])?$entityManager->getRepository(TipoCategoria::class)->find($question["categoryId"]):null);
                        $pregunta->setIdInstrumento($entity);
                        $pregunta->setSeccion($seccion);
                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                         if($empresa)
                           $pregunta->setIdempresa($empresa);
                        $entityManager->persist($pregunta);
                        $entityManager->flush();    
                        if(isset($question["options"])){
                            foreach($question["options"] as $options){
                                $opciones = new Opciones();
                                $opciones->setCorrecta(1);
                                $opciones->setNombre(!is_null($options["label"])?$options["label"]:null);
                                $opciones->setValor(!is_null($options["value"])?$options["value"]:null);
                                if($data["puntosGlobales"]==1){
                                    $opciones->setPuntos(!is_null($options["score"])?$options["score"]:null);
                                }
                                $opciones->setIdPregunta($pregunta);
                                $opciones->setUpdateAt(new \DateTime());
                                $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
                                $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                if($empresa)
                                   $opciones->setIdempresa($empresa);
                                $opciones->setUpdateBy($currentUser->getUserName());                                
                                $entityManager->persist($opciones);
                                $entityManager->flush();    
                                if(isset($options["scoreBycharges"])){
                                    foreach($options["scoreBycharges"] as $optionsCargos){
                                        $opcionesCargos = new OpcionesCargo();
                                        $cargo =$entityManager->getRepository(Cargo::class)->find($optionsCargos["idCargo"]);
                                        $opcionesCargos->setIdCargo($cargo!=null?$cargo:null);
                                        $opcionesCargos->setOpcion($opciones);
                                        $opcionesCargos->setScore($optionsCargos["score"]);
                                        $opcionesCargos->setCreateBy($currentUser->getUsername());
                                        $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
                                        if($empresa)
                                           $opcionesCargos->setIdempresa($empresa);

                                        $entityManager->persist($opcionesCargos);
                                        $entityManager->flush();    
        
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return new JsonResponse(['msg'=>'Registro Creado','id'=>$entity->getId()],200);
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
