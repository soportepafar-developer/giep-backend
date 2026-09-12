<?php

namespace App\Repository\Instrumento360;

use App\Entity\Instrumento360\Instrumento360;
use App\Entity\Instrumento360\Instrumento360UsuariosAsignados;
// use App\Entity\Instrumento360\Odis; // TODO ODIS
// use App\Entity\Instrumento360\OdisObjetivos; // TODO ODIS
use App\Entity\Instrumento360\OpcionesEvaluacion360;
use App\Entity\Instrumento360\PreguntaEvaluacion360;
// use App\Entity\Instrumento360\RangosOdis; // TODO ODIS
use App\Entity\Instrumento360\RespuestaEvaluacion360;
use App\Entity\Proyecto\Empresa;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;

/**
 * @method RespuestaEvaluacion360|null find($id, $lockMode = null, $lockVersion = null)
 * @method RespuestaEvaluacion360|null findOneBy(array $criteria, array $orderBy = null)
 * @method RespuestaEvaluacion360[]    findAll()
 * @method RespuestaEvaluacion360[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RespuestaEvaluacion360Repository extends ServiceEntityRepository
{
    private $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, RespuestaEvaluacion360::class);
    }

    /**
     * Guarda las respuestas del evaluador autenticado para un usuario evaluado.
     *
     * Merge prod + local:
     * - Prod: mensajes, retorno {msg,id}, persistencia de respuestas
     * - Local: fallback de asignación, anti-duplicados, un flush final
     *
     * Payload: { id, userId, questions: [{ id, response: [{ idOption, text }] }] }
     * ODIS/objetivos: pendiente
     */
    public function post($data, $validator, $helper): JsonResponse
    {
        $entityManager = $this->getEntityManager();
        $currentUser = $entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        if (!$currentUser) {
            return new JsonResponse(['msg' => 'Usuario autenticado no encontrado'], 401);
        }

        if (!isset($data['id']) || !isset($data['userId']) || !isset($data['questions']) || !is_array($data['questions'])) {
            return new JsonResponse(['msg' => 'Datos incompletos. Se requiere id, userId y questions'], 400);
        }

        $instrumento = $entityManager->getRepository(Instrumento360::class)->find($data['id']);
        if (!$instrumento) {
            return new JsonResponse(['msg' => 'No existe el instrumento 360'], 404);
        }

        $userEvaluado = $entityManager->getRepository(User::class)->find($data['userId']);
        if (!$userEvaluado) {
            return new JsonResponse(['msg' => 'No existe el usuario evaluado'], 404);
        }

        // respondida es por evaluador: solo la fila instrumento + evaluado + evaluador actual
        $instrumentoUsuario = $entityManager->getRepository(Instrumento360UsuariosAsignados::class)->findOneBy([
            'instrumento360' => $instrumento,
            'user' => $userEvaluado,
            'userEvaluador' => $currentUser,
        ]);

        // Legacy: asignación sin evaluador (null) — se atribuye al evaluador actual
        if ($instrumentoUsuario === null) {
            $legacy = $entityManager->getRepository(Instrumento360UsuariosAsignados::class)->findOneBy([
                'instrumento360' => $instrumento,
                'user' => $userEvaluado,
                'userEvaluador' => null,
            ]);
            if ($legacy !== null) {
                $legacy->setUserEvaluador($currentUser);
                $instrumentoUsuario = $legacy;
            }
        }

        if (!$instrumentoUsuario) {
            return new JsonResponse(['msg' => 'No existe asignación para evaluar a este usuario'], 404);
        }

        if ((int) $instrumentoUsuario->getRespondida() === 1) {
            return new JsonResponse([
                'msg' => 'La evaluación ya fue respondida por el evaluador: ' . $currentUser->getUserName(),
            ], 409);
        }

        $empresa = null;
        if ($this->security->getUser()->getIdempresa()) {
            $empresa = $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
        }

        $lastEntityId = null;

        foreach ($data['questions'] as $valueQuestion) {
            if (empty($valueQuestion['id']) || empty($valueQuestion['response']) || !is_array($valueQuestion['response'])) {
                continue;
            }

            $idQuestion = $valueQuestion['id'];
            $entityPregunta = $entityManager->getRepository(PreguntaEvaluacion360::class)->find($idQuestion);
            if (!$entityPregunta) {
                return new JsonResponse(['msg' => 'No existe la pregunta: ' . $idQuestion], 404);
            }

            foreach ($valueQuestion['response'] as $value) {
                $idOption = isset($value['idOption']) ? $value['idOption'] : null;

                // Anti-dup por evaluador (createBy = username del evaluador actual)
                $existsCriteria = [
                    'idUser' => $userEvaluado,
                    'idPregunta' => $entityPregunta,
                    'createBy' => $currentUser->getUserName(),
                ];
                if ($idOption != null) {
                    $existsCriteria['idOpcion'] = $entityManager->getRepository(OpcionesEvaluacion360::class)->find($idOption);
                }
                $exists = $entityManager->getRepository(RespuestaEvaluacion360::class)->findOneBy($existsCriteria);

                if ($exists === null && ($idOption === null || $idOption === '')) {
                    $exists = $entityManager->getRepository(RespuestaEvaluacion360::class)->findOneBy([
                        'idUser' => $userEvaluado,
                        'idPregunta' => $entityPregunta,
                        'createBy' => $currentUser->getUserName(),
                    ]);
                }

                if ($exists !== null) {
                    $lastEntityId = $exists->getId();
                    continue;
                }

                $entity = new RespuestaEvaluacion360();
                $entity->setIdUser($userEvaluado);
                $entity->setIdPregunta($entityPregunta);

                if ($idOption != null) {
                    $entityOpcion = $entityManager->getRepository(OpcionesEvaluacion360::class)->find($idOption);
                    if ($entityOpcion != null) {
                        $entity->setIdOpcion($entityOpcion);
                    }
                }

                if ($idOption == null && isset($value['text'])) {
                    $entity->setEntradaTexto($value['text']);
                } elseif (isset($value['text']) && $value['text'] !== null && $value['text'] !== '') {
                    $entity->setEntradaTexto((string) $value['text']);
                }

                $now = new \DateTime();
                $entity->setCreateAt($now);
                $entity->setUpdateAt($now);
                $entity->setCreateBy($currentUser->getUserName());
                $entity->setUpdateBy($currentUser->getUserName());
                if ($empresa) {
                    $entity->setIdempresa($empresa);
                }

                $errors = $validator->validate($entity);
                if ($errors->count() > 0) {
                    $messages = [];
                    foreach ($errors as $violation) {
                        $messages[$violation->getPropertyPath()][] = $violation->getMessage();
                    }
                    return new JsonResponse($messages, 409);
                }

                $entityManager->persist($entity);
                $entityManager->flush();
                $lastEntityId = $entity->getId();
            }
        }

        // TODO ODIS: reactivar cuando se implemente persistencia de objetivos

        $instrumentoUsuario->setRespondida(1);
        $instrumentoUsuario->setUpdateAt(new \DateTime());
        $instrumentoUsuario->setUpdateBy($currentUser->getUserName());
        if ($empresa && method_exists($instrumentoUsuario, 'setEmpresa')) {
            $instrumentoUsuario->setEmpresa($empresa);
        }
        $entityManager->persist($instrumentoUsuario);
        $entityManager->flush();

        return new JsonResponse(['msg' => 'Registro Creado', 'id' => $lastEntityId], 200);
    }
}
