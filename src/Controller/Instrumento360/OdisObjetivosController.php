<?php

namespace App\Controller\Instrumento360;

use App\Entity\Instrumento360\OdisObjetivos;
use App\Entity\Instrumento360\Odis;
use App\Entity\Instrumento360\RangosOdis;
use App\Repository\Instrumento360\OdisObjetivosRepository;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @OA\Tag(name="OdisObjetivos")
 * @Route("/api/odis-objetivos")
 */
class OdisObjetivosController extends AbstractController
{
    private $repository;

    public function __construct(OdisObjetivosRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("", methods={"POST"})
     * @OA\Post(
     *   summary="Crear OdisObjetivos",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"odi", "odirango"},
     *       @OA\Property(property="odi", type="integer"),
     *       @OA\Property(property="odirango", type="integer")
     *     )
     *   ),
     *   @OA\Response(response=200, description="OdisObjetivos creado")
     * )
     */
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        $odi = $em->getRepository(Odis::class)->find($data['odi']);
        $odirango = $em->getRepository(RangosOdis::class)->find($data['odirango']);
        if (!$odi || !$odirango) {
            return new JsonResponse(['msg' => 'Odi o odirango no encontrado'], 400);
        }
        $entity = new OdisObjetivos();
        $entity->setOdi($odi);
        $entity->setOdirango($odirango);
        $errors = $validator->validate($entity);
        if (count($errors) > 0) {
            return new JsonResponse(['msg' => (string) $errors], 400);
        }
        $em->persist($entity);
        $em->flush();
        return new JsonResponse(['msg' => 'OdisObjetivos creado', 'id' => $entity->getId()], 200);
    }

    /**
     * @Route("", methods={"GET"})
     * @OA\Get(summary="Listar OdisObjetivos", @OA\Response(response=200, description="Lista de OdisObjetivos"))
     */
    public function list(): JsonResponse
    {
        $objetivos = $this->repository->findAll();
        $data = [];
        foreach ($objetivos as $obj) {
            $data[] = [
                'id' => $obj->getId(),
                'odi' => $obj->getOdi() ? $obj->getOdi()->getId() : null,
                'odirango' => $obj->getOdirango() ? $obj->getOdirango()->getId() : null,
            ];
        }
        return new JsonResponse($data, 200);
    }

    /**
     * @Route("/{id}", methods={"GET"})
     * @OA\Get(summary="Obtener OdisObjetivos", @OA\Response(response=200, description="OdisObjetivos encontrado"))
     */
    public function getOne($id): JsonResponse
    {
        $obj = $this->repository->find($id);
        if (!$obj) {
            return new JsonResponse(['msg' => 'No existe el objetivo con el id: ' . $id], 404);
        }
        $data = [
            'id' => $obj->getId(),
            'odi' => $obj->getOdi() ? $obj->getOdi()->getId() : null,
            'odirango' => $obj->getOdirango() ? $obj->getOdirango()->getId() : null,
        ];
        return new JsonResponse($data, 200);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     * @OA\Put(
     *   summary="Actualizar OdisObjetivos",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="odi", type="integer"),
     *       @OA\Property(property="odirango", type="integer")
     *     )
     *   ),
     *   @OA\Response(response=200, description="OdisObjetivos actualizado")
     * )
     */
    public function update($id, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $obj = $this->repository->find($id);
        if (!$obj) {
            return new JsonResponse(['msg' => 'No existe el objetivo con el id: ' . $id], 404);
        }
        $data = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        if (isset($data['odi'])) {
            $odi = $em->getRepository(Odis::class)->find($data['odi']);
            if ($odi) $obj->setOdi($odi);
        }
        if (isset($data['odirango'])) {
            $odirango = $em->getRepository(RangosOdis::class)->find($data['odirango']);
            if ($odirango) $obj->setOdirango($odirango);
        }
        $errors = $validator->validate($obj);
        if (count($errors) > 0) {
            return new JsonResponse(['msg' => (string) $errors], 400);
        }
        $em->flush();
        return new JsonResponse(['msg' => 'OdisObjetivos actualizado'], 200);
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     * @OA\Delete(summary="Eliminar OdisObjetivos", @OA\Response(response=200, description="OdisObjetivos eliminado"))
     */
    public function delete($id): JsonResponse
    {
        $obj = $this->repository->find($id);
        if (!$obj) {
            return new JsonResponse(['msg' => 'No existe el objetivo con el id: ' . $id], 404);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($obj);
        $em->flush();
        return new JsonResponse(['msg' => 'OdisObjetivos eliminado'], 200);
    }
} 