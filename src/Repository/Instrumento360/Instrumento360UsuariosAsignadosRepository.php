<?php

namespace App\Repository\Instrumento360;

use App\Entity\Instrumento360\Instrumento360UsuariosAsignados;
use App\Entity\Instrumento360\Instrumento360;
use App\Entity\User;
use App\Entity\EstructuraOrganizativa;
use App\Entity\Cargo;
use App\Entity\Proyecto\Empresa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;

/**
 * @extends ServiceEntityRepository<Instrumento360UsuariosAsignados>
 *
 * @method Instrumento360UsuariosAsignados|null find($id, $lockMode = null, $lockVersion = null)
 * @method Instrumento360UsuariosAsignados|null findOneBy(array $criteria, array $orderBy = null)
 * @method Instrumento360UsuariosAsignados[]    findAll()
 * @method Instrumento360UsuariosAsignados[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Instrumento360UsuariosAsignadosRepository extends ServiceEntityRepository
{
    private $security;
    private $validator;
    private $helper;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Instrumento360UsuariosAsignados::class);
    }


        public function asignarUsuarioAInstrumento($data, $validator, $helper): JsonResponse
        {
            $entityManager = $this->getEntityManager();

            // Buscar usuario
            $user = $entityManager->getRepository(User::class)->find($data["userId"]);
            if (!$user) {
                return new JsonResponse(['msg' => 'No existe el usuario: ' . $userId], 404);
            }

            // Buscar instrumento
            $instrumento = $entityManager->getRepository(Instrumento360::class)->find($data["instrumentoId"]);
            if (!$instrumento) {
                return new JsonResponse(['msg' => 'No existe el instrumento: ' . $instrumento], 404);
            }

            // Buscar unidad organizativa del usuario (ajusta el método según tu entidad User)
            $unidadUser = method_exists($user, 'getUnidadOrganizativa') ? $user->getUnidadOrganizativa() : null;
            // if (!$unidadUser) {
            //     return new JsonResponse(['msg' => 'El usuario no tiene unidad organizativa asignada'], 400);
            // }
            $currentUser =$entityManager->getRepository(User::class)->find($this->security->getUser()->getId());


            $entity = new Instrumento360UsuariosAsignados();
            $entity->setUser($user);
           // $entity->setUnidadUser($unidadUser);
            $entity->setUnidadUser($unidadUser);
            $entity->setInstrumento360($instrumento);
            $entity->setUserEvaluador($currentUser);
            $entity->setCreateAt(new \DateTime());
            $entity->setCreateBy($this->security->getUser()->getUserName());
            $empresa = $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if ($empresa) {
                $entity->setEmpresa($empresa);
            }

            $errors = $validator->validate($entity);
            if($errors->count() > 0) {
                $errorsString = (string) $errors;
                return new JsonResponse(['msg' => $errorsString], 500);
            }

            $entityManager->persist($entity);
            $entityManager->flush();

            return new JsonResponse([
                'msg' => 'Registro Creado',
                'id' => $entity->getId()
            ], 200);
        }


    public function post($data, $validator, $helper): JsonResponse
    {
        $entityManager = $this->getEntityManager();
        $entity = $helper->setParametersToEntity(new Instrumento360UsuariosAsignados(), $data);
        
        $errors = $validator->validate($entity);
        if($errors->count() > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse(['msg' => $errorsString], 500);
        } else {
            // Validar y asignar el usuario
            $user = $entityManager->getRepository(User::class)->find($data['userId']);
            if (!$user) {
                return new JsonResponse(['msg' => 'No existe el usuario: ' . $data['userId']], 404);
            }
            $entity->setUser($user);

            // Validar y asignar la unidad del usuario
            $unidadUser = $entityManager->getRepository(EstructuraOrganizativa::class)->find($data['unidadUserId']);
            if (!$unidadUser) {
                return new JsonResponse(['msg' => 'No existe la unidad del usuario: ' . $data['unidadUserId']], 404);
            }
            $entity->setUnidadUser($unidadUser);

            // Validar y asignar el usuario evaluador
            $userEvaluador = $entityManager->getRepository(User::class)->find($data['userEvaluadorId']);
            if (!$userEvaluador) {
                return new JsonResponse(['msg' => 'No existe el usuario evaluador: ' . $data['userEvaluadorId']], 404);
            }
            $entity->setUserEvaluador($userEvaluador);

            // Validar y asignar la unidad del evaluador
            if (isset($data['unidadEvaluadorId'])) {
                $unidadEvaluador = $entityManager->getRepository(EstructuraOrganizativa::class)->find($data['unidadEvaluadorId']);
                if ($unidadEvaluador) {
                    $entity->setUnidadEvaluador($unidadEvaluador);
                }
            }

            // Validar y asignar el cargo del usuario
            if (isset($data['cargoUserId'])) {
                $cargoUser = $entityManager->getRepository(Cargo::class)->find($data['cargoUserId']);
                if ($cargoUser) {
                    $entity->setCargoUser($cargoUser);
                }
            }

            // Validar y asignar el cargo del evaluador
            if (isset($data['cargoEvaluadorId'])) {
                $cargoEvaluador = $entityManager->getRepository(Cargo::class)->find($data['cargoEvaluadorId']);
                if ($cargoEvaluador) {
                    $entity->setCargoEvaluador($cargoEvaluador);
                }
            }

            // Validar y asignar el instrumento 360
            $instrumento360 = $entityManager->getRepository(Instrumento360::class)->find($data['instrumento360Id']);
            if (!$instrumento360) {
                return new JsonResponse(['msg' => 'No existe el instrumento 360: ' . $data['instrumento360Id']], 404);
            }
            $entity->setInstrumento360($instrumento360);

            // Asignar empresa
            $empresa = $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if ($empresa) {
                $entity->setEmpresa($empresa);
            }

            // Asignar datos de auditoría
            $currentUser = $entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $entity->setCreateAt(new \DateTime());


            $entityManager->persist($entity);
            $entityManager->flush();
            
            return new JsonResponse([
                'msg' => 'Registro Creado',
                'id' => $entity->getId()
            ], 200);
        }
    }

    public function put($data, $id, $validator, $helper): JsonResponse
    {
        $entityManager = $this->getEntityManager();
        $entity = $this->find($id);

        if (!$entity) {
            return new JsonResponse(['msg' => 'No existe el registro con id: ' . $id], 404);
        }

        $entity = $helper->setParametersToEntity($entity, $data);
        
        $errors = $validator->validate($entity);
        if($errors->count() > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse(['msg' => $errorsString], 500);
        } else {
            // Actualizar campos si están presentes en los datos
            if (isset($data['userId'])) {
                $user = $entityManager->getRepository(User::class)->find($data['userId']);
                if (!$user) {
                    return new JsonResponse(['msg' => 'No existe el usuario: ' . $data['userId']], 404);
                }
                $entity->setUser($user);
            }

            if (isset($data['unidadUserId'])) {
                $unidadUser = $entityManager->getRepository(EstructuraOrganizativa::class)->find($data['unidadUserId']);
                if (!$unidadUser) {
                    return new JsonResponse(['msg' => 'No existe la unidad del usuario: ' . $data['unidadUserId']], 404);
                }
                $entity->setUnidadUser($unidadUser);
            }

            if (isset($data['userEvaluadorId'])) {
                $userEvaluador = $entityManager->getRepository(User::class)->find($data['userEvaluadorId']);
                if (!$userEvaluador) {
                    return new JsonResponse(['msg' => 'No existe el usuario evaluador: ' . $data['userEvaluadorId']], 404);
                }
                $entity->setUserEvaluador($userEvaluador);
            }

            if (isset($data['unidadEvaluadorId'])) {
                $unidadEvaluador = $entityManager->getRepository(EstructuraOrganizativa::class)->find($data['unidadEvaluadorId']);
                if ($unidadEvaluador) {
                    $entity->setUnidadEvaluador($unidadEvaluador);
                }
            }

            if (isset($data['cargoUserId'])) {
                $cargoUser = $entityManager->getRepository(Cargo::class)->find($data['cargoUserId']);
                if ($cargoUser) {
                    $entity->setCargoUser($cargoUser);
                }
            }

            if (isset($data['cargoEvaluadorId'])) {
                $cargoEvaluador = $entityManager->getRepository(Cargo::class)->find($data['cargoEvaluadorId']);
                if ($cargoEvaluador) {
                    $entity->setCargoEvaluador($cargoEvaluador);
                }
            }

            if (isset($data['instrumento360Id'])) {
                $instrumento360 = $entityManager->getRepository(Instrumento360::class)->find($data['instrumento360Id']);
                if (!$instrumento360) {
                    return new JsonResponse(['msg' => 'No existe el instrumento 360: ' . $data['instrumento360Id']], 404);
                }
                $entity->setInstrumento360($instrumento360);
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

    public function delete($id, $validator, $helper): JsonResponse
    {
        $entityManager = $this->getEntityManager();
        $entity = $this->find($id);

        if (!$entity) {
            return new JsonResponse(['msg' => 'No existe el registro con id: ' . $id], 404);
        }

        try {
            $entityManager->remove($entity);
            $entityManager->flush();
            
            return new JsonResponse([
                'msg' => 'Registro Eliminado',
                'id' => $id
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['msg' => 'Error al eliminar el registro: ' . $e->getMessage()], 500);
        }
    }

    public function findById($id): JsonResponse
    {
        $entity = $this->find($id);

        if (!$entity) {
            return new JsonResponse(['msg' => 'No existe el registro con id: ' . $id], 404);
        }

        $data = [
            'id' => $entity->getId(),
            'userId' => $entity->getUser() ? $entity->getUser()->getId() : null,
            'unidadUserId' => $entity->getUnidadUser() ? $entity->getUnidadUser()->getId() : null,
            'userEvaluadorId' => $entity->getUserEvaluador() ? $entity->getUserEvaluador()->getId() : null,
            'unidadEvaluadorId' => $entity->getUnidadEvaluador() ? $entity->getUnidadEvaluador()->getId() : null,
            'cargoUserId' => $entity->getCargoUser() ? $entity->getCargoUser()->getId() : null,
            'cargoEvaluadorId' => $entity->getCargoEvaluador() ? $entity->getCargoEvaluador()->getId() : null,
            'instrumento360Id' => $entity->getInstrumento360() ? $entity->getInstrumento360()->getId() : null,
            'createAt' => $entity->getCreateAt() ? $entity->getCreateAt()->format('Y-m-d H:i:s') : null,
            'createBy' => $entity->getCreateBy(),
            'updateAt' => $entity->getUpdateAt() ? $entity->getUpdateAt()->format('Y-m-d H:i:s') : null,
            'updateBy' => $entity->getUpdateBy()
        ];

        return new JsonResponse(['data' => $data], 200);
    }
} 