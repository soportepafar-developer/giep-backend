<?php

namespace App\Controller\Instrumento360;

use App\Entity\Instrumento360\RespuestaEvaluacion360;
use App\Repository\Instrumento360\RespuestaEvaluacion360Repository;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\Helper;

class RespuestaEvaluacion360Controller extends AbstractController
{
    /**
     * Guardar respuestas de una evaluación 360 para un usuario evaluado.
     *
     * @Route("/api/instrumento360/respuesta", methods={"POST"})
     * @OA\Post(
     *   summary="Create Respuesta Evaluación 360",
     *   description="Persiste las respuestas del instrumento 360 para un usuario evaluado y marca la asignación como respondida.",
     *   operationId="createRespuestaEvaluacion360",
     *   tags={"Respuesta Evaluacion 360"},
     *   @OA\RequestBody(
     *     required=true,
     *     description="Payload respuesta 360",
     *     @OA\JsonContent(
     *       required={"id","userId","questions"},
     *       @OA\Property(property="id", type="integer", example=10, description="ID instrumento 360"),
     *       @OA\Property(property="userId", type="integer", example=25, description="ID usuario evaluado"),
     *       @OA\Property(
     *         property="questions",
     *         type="array",
     *         @OA\Items(
     *           type="object",
     *           @OA\Property(property="id", type="integer", example=65),
     *           @OA\Property(
     *             property="response",
     *             type="array",
     *             @OA\Items(
     *               type="object",
     *               @OA\Property(property="idOption", type="integer", nullable=true, example=4),
     *               @OA\Property(property="text", type="string", nullable=true, example=null)
     *             )
     *           )
     *         )
     *       )
     *     )
     *   ),
     *   @OA\Response(response=200, description="Registro creado"),
     *   @OA\Response(response=400, description="Payload inválido"),
     *   @OA\Response(response=409, description="Validación / ya respondida")
     * )
     * @Security(name="Bearer")
     */
    public function post(Request $request, ValidatorInterface $validator, Helper $helper): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            if (!is_array($data)) {
                return new JsonResponse(['msg' => 'JSON inválido'], 400);
            }
            /** @var RespuestaEvaluacion360Repository $repository */
            $repository = $this->getDoctrine()->getRepository(RespuestaEvaluacion360::class);
            return $repository->post($data, $validator, $helper);
        } catch (\Exception $e) {
            return new JsonResponse(['msg' => 'Error del Servidor', 'error' => $e->getMessage()], 500);
        }
    }
}
