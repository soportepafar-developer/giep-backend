<?php

namespace App\Controller\Instrumento360;

use App\Repository\Instrumento360\Instrumento360EvaluacionesRepository;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

/**
 * @OA\Tag(name="Instrumento360 Evaluaciones")
 */
class Instrumento360EvaluacionesController extends AbstractController
{
    private $evaluacionesRepository;

    public function __construct(Instrumento360EvaluacionesRepository $evaluacionesRepository)
    {
        $this->evaluacionesRepository = $evaluacionesRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/instrumento360/evaluaciones",
     *     summary="Crear una nueva evaluación 360",
     *     operationId="evaluaciones360tipocreate",
     *     tags={"Instrumento360 Evaluaciones"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="idinstrumentousuario", type="integer"),
     *             @OA\Property(property="idcompetencia360", type="integer"),
     *             @OA\Property(property="idniveldominio", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Evaluación creada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error de validación"
     *     )
     * )
     * @Route("/api/instrumento360/evaluaciones", methods={"POST"})
     */
    public function create(Request $request,ValidatorInterface $validator,Helper $helper,Instrumento360EvaluacionesRepository $EvaluacionesRepository): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(),true);
            return $EvaluacionesRepository->post($data,$validator,$helper);
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/instrumento360/evaluaciones/{id}",
     *     summary="Actualizar una evaluación 360",
     *     operationId="evaluaciones360tipoUpdate",
     *     tags={"Instrumento360 Evaluaciones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="idinstrumentousuario", type="integer"),
     *             @OA\Property(property="idcompetencia360", type="integer"),
     *             @OA\Property(property="idniveldominio", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Evaluación actualizada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error de validación"
     *     )
     * )
     * @Route("/api/instrumento360/evaluaciones/{id}", methods={"PUT"})
     */
    public function update($id, Request $request, ValidatorInterface $validator, Helper $helper): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            return $this->evaluacionesRepository->put($data, $id, $validator, $helper);
        } catch (\Exception $e) {
            return new JsonResponse(['msg' => 'Error del Servidor'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/instrumento360/evaluaciones/{id}",
     *     summary="Obtener una evaluación 360 por ID",
     *     operationId="evaluaciones360tipoGet",
     *     tags={"Instrumento360 Evaluaciones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Evaluación obtenida exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Evaluación no encontrada"
     *     )
     * )
     * @Route("/api/instrumento360/evaluaciones/{id}", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        try {
            return $this->evaluacionesRepository->get($id);
        } catch (\Exception $e) {
            return new JsonResponse(['msg' => 'Error del Servidor'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/instrumento360/evaluaciones/{id}",
     *     summary="Eliminar una evaluación 360",
     *     operationId="evaluaciones360tipoDelete",
     *     tags={"Instrumento360 Evaluaciones"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Evaluación eliminada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Evaluación no encontrada"
     *     )
     * )
     * @Route("/api/instrumento360/evaluaciones/{id}", methods={"DELETE"})
     */
    public function delete($id, ValidatorInterface $validator, Helper $helper): JsonResponse
    {
        try {
            return $this->evaluacionesRepository->delete($id, $validator, $helper);
        } catch (\Exception $e) {
            return new JsonResponse(['msg' => 'Error del Servidor'], 500);
        }
    }
} 