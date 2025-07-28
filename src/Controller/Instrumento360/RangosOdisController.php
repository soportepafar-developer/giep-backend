<?php

namespace App\Controller\Instrumento360;

use App\Entity\Instrumento360\RangosOdis;
use App\Repository\Instrumento360\RangosOdisRepository;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @OA\Tag(name="RangosOdis")
 * @Route("/api/rangos-odis")
 */
class RangosOdisController extends AbstractController
{
    private $repository;

    public function __construct(RangosOdisRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("", methods={"POST"})
     * @OA\Post(
     *   summary="Crear Rango Odis",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"rango"},
     *       @OA\Property(property="rango", type="integer"),
     *       @OA\Property(property="descripcion", type="string"),          
     *       @OA\Property(property="porcentaje", type="number", format="float")
     *     )
     *   ),
     *   @OA\Response(response=200, description="Rango Odis creado")
     * )
     */
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $entity = new RangosOdis();
        $entity->setRango($data['rango']);
        $entity->setPorcentaje($data['porcentaje'] ?? null);
        $errors = $validator->validate($entity);
        if (count($errors) > 0) {
            return new JsonResponse(['msg' => (string) $errors], 400);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();
        return new JsonResponse(['msg' => 'Rango Odis creado', 'id' => $entity->getId()], 200);
    }

    /**
     * @Route("", methods={"GET"})
     * @OA\Get(summary="Listar Rangos Odis", @OA\Response(response=200, description="Lista de rangos"))
     */
    public function list(): JsonResponse
    {
        $rangos = $this->repository->findAll();
        $data = [];
        foreach ($rangos as $rango) {
            $data[] = [
                'id' => $rango->getRango(),
                'label' => $rango->getDescripcion(),
                'porcentaje' => $rango->getPorcentaje(),
            ];
        }
        return new JsonResponse($data, 200);
    }

    /**
     * @Route("/{id}", methods={"GET"})
     * @OA\Get(summary="Obtener Rango Odis", @OA\Response(response=200, description="Rango encontrado"))
     */
    public function getOne($id): JsonResponse
    {
        $rango = $this->repository->find($id);
        if (!$rango) {
            return new JsonResponse(['msg' => 'No existe el rango con el id: ' . $id], 404);
        }
        $data = [
            'id' => $rango->getId(),
            'rango' => $rango->getRango(),
            'porcentaje' => $rango->getPorcentaje(),
        ];
        return new JsonResponse($data, 200);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     * @OA\Put(
     *   summary="Actualizar Rango Odis",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="rango", type="integer"),
     *       @OA\Property(property="descripcion", type="string"),          
     *       @OA\Property(property="porcentaje", type="number", format="float")
     *     )
     *   ),
     *   @OA\Response(response=200, description="Rango Odis actualizado")
     * )
     */
    public function update($id, Request $request, ValidatorInterface $validator): JsonResponse
    {
        $rango = $this->repository->find($id);
        if (!$rango) {
            return new JsonResponse(['msg' => 'No existe el rango con el id: ' . $id], 404);
        }
        $data = json_decode($request->getContent(), true);
        if (isset($data['rango'])) $rango->setRango($data['rango']);
        if (array_key_exists('porcentaje', $data)) $rango->setPorcentaje($data['porcentaje']);
        $errors = $validator->validate($rango);
        if (count($errors) > 0) {
            return new JsonResponse(['msg' => (string) $errors], 400);
        }
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return new JsonResponse(['msg' => 'Rango Odis actualizado'], 200);
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     * @OA\Delete(summary="Eliminar Rango Odis", @OA\Response(response=200, description="Rango eliminado"))
     */
    public function delete($id): JsonResponse
    {
        $rango = $this->repository->find($id);
        if (!$rango) {
            return new JsonResponse(['msg' => 'No existe el rango con el id: ' . $id], 404);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($rango);
        $em->flush();
        return new JsonResponse(['msg' => 'Rango Odis eliminado'], 200);
    }
} 