<?php

namespace App\Controller\Instrumento360;

use App\Entity\Instrumento360\Odis;
use App\Entity\Instrumento360\Instrumento360;
use App\Entity\Cargo;
use App\Entity\User;
use App\Repository\Instrumento360\OdisRepository;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @OA\Tag(name="Odis")
 * @Route("/api/odis")
 */
class OdisController extends AbstractController
{
    private $repository;

    public function __construct(OdisRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("", methods={"POST"})
     * @OA\Post(
     *   summary="Crear Odis",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"instrumento360"},
     *       @OA\Property(property="instrumento360", type="integer"),
     *       @OA\Property(property="user", type="integer"),
     *       @OA\Property(property="userCargo", type="integer"),
     *       @OA\Property(property="userEvaluador", type="integer"),
     *       @OA\Property(property="cargoEvaluador", type="integer")
     *     )
     *   ),
     *   @OA\Response(response=200, description="Odis creado")
     * )
     */
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $instrumento = $em->getRepository(Instrumento360::class)->find($data['instrumento360']);
        if (!$instrumento) {
            return new JsonResponse(['msg' => 'Instrumento360 no encontrado'], 400);
        }
        $entity = new Odis();
        $entity->setInstrumento360($instrumento);
        if (isset($data['user'])) {
            $user = $em->getRepository(User::class)->find($data['user']);
            if ($user) $entity->setUser($user);
        }
        if (isset($data['userCargo'])) {
            $cargo = $em->getRepository(Cargo::class)->find($data['userCargo']);
            if ($cargo) $entity->setUserCargo($cargo);
        }
        if (isset($data['userEvaluador'])) {
            $userEval = $em->getRepository(User::class)->find($data['userEvaluador']);
            if ($userEval) $entity->setUserEvaluador($userEval);
        }
        if (isset($data['cargoEvaluador'])) {
            $cargoEval = $em->getRepository(Cargo::class)->find($data['cargoEvaluador']);
            if ($cargoEval) $entity->setCargoEvaluador($cargoEval);
        }
        $errors = $validator->validate($entity);
        if (count($errors) > 0) {
            return new JsonResponse(['msg' => (string) $errors], 400);
        }
        $em->persist($entity);
        $em->flush();
        return new JsonResponse(['msg' => 'Odis creado', 'id' => $entity->getId()], 200);
    }

    /**
     * @Route("", methods={"GET"})
     * @OA\Get(summary="Listar Odis", @OA\Response(response=200, description="Lista de Odis"))
     */
    public function list(): JsonResponse
    {
        $odis = $this->repository->findAll();
        $data = [];
        foreach ($odis as $odi) {
            $data[] = [
                'id' => $odi->getId(),
                'instrumento360' => $odi->getInstrumento360() ? $odi->getInstrumento360()->getId() : null,
                'user' => $odi->getUser() ? $odi->getUser()->getId() : null,
                'userCargo' => $odi->getUserCargo() ? $odi->getUserCargo()->getId() : null,
                'userEvaluador' => $odi->getUserEvaluador() ? $odi->getUserEvaluador()->getId() : null,
                'cargoEvaluador' => $odi->getCargoEvaluador() ? $odi->getCargoEvaluador()->getId() : null,
            ];
        }
        return new JsonResponse($data, 200);
    }

    /**
     * @Route("/{id}", methods={"GET"})
     * @OA\Get(summary="Obtener Odis", @OA\Response(response=200, description="Odis encontrado"))
     */
    public function getOne($id): JsonResponse
    {
        $odi = $this->repository->find($id);
        if (!$odi) {
            return new JsonResponse(['msg' => 'No existe el Odis con el id: ' . $id], 404);
        }
        $data = [
            'id' => $odi->getId(),
            'instrumento360' => $odi->getInstrumento360() ? $odi->getInstrumento360()->getId() : null,
            'user' => $odi->getUser() ? $odi->getUser()->getId() : null,
            'userCargo' => $odi->getUserCargo() ? $odi->getUserCargo()->getId() : null,
            'userEvaluador' => $odi->getUserEvaluador() ? $odi->getUserEvaluador()->getId() : null,
            'cargoEvaluador' => $odi->getCargoEvaluador() ? $odi->getCargoEvaluador()->getId() : null,
        ];
        return new JsonResponse($data, 200);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     * @OA\Put(
     *   summary="Actualizar Odis",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="instrumento360", type="integer"),
     *       @OA\Property(property="user", type="integer"),
     *       @OA\Property(property="userCargo", type="integer"),
     *       @OA\Property(property="userEvaluador", type="integer"),
     *       @OA\Property(property="cargoEvaluador", type="integer")
     *     )
     *   ),
     *   @OA\Response(response=200, description="Odis actualizado")
     * )
     */
    public function update($id, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $odi = $this->repository->find($id);
        if (!$odi) {
            return new JsonResponse(['msg' => 'No existe el Odis con el id: ' . $id], 404);
        }
        $data = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        if (isset($data['instrumento360'])) {
            $instrumento = $em->getRepository(Instrumento360::class)->find($data['instrumento360']);
            if ($instrumento) $odi->setInstrumento360($instrumento);
        }
        if (isset($data['user'])) {
            $user = $em->getRepository(User::class)->find($data['user']);
            if ($user) $odi->setUser($user);
        }
        if (isset($data['userCargo'])) {
            $cargo = $em->getRepository(Cargo::class)->find($data['userCargo']);
            if ($cargo) $odi->setUserCargo($cargo);
        }
        if (isset($data['userEvaluador'])) {
            $userEval = $em->getRepository(User::class)->find($data['userEvaluador']);
            if ($userEval) $odi->setUserEvaluador($userEval);
        }
        if (isset($data['cargoEvaluador'])) {
            $cargoEval = $em->getRepository(Cargo::class)->find($data['cargoEvaluador']);
            if ($cargoEval) $odi->setCargoEvaluador($cargoEval);
        }
        $errors = $validator->validate($odi);
        if (count($errors) > 0) {
            return new JsonResponse(['msg' => (string) $errors], 400);
        }
        $em->flush();
        return new JsonResponse(['msg' => 'Odis actualizado'], 200);
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     * @OA\Delete(summary="Eliminar Odis", @OA\Response(response=200, description="Odis eliminado"))
     */
    public function delete($id): JsonResponse
    {
        $odi = $this->repository->find($id);
        if (!$odi) {
            return new JsonResponse(['msg' => 'No existe el Odis con el id: ' . $id], 404);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($odi);
        $em->flush();
        return new JsonResponse(['msg' => 'Odis eliminado'], 200);
    }
} 