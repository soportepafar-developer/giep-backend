<?php

namespace App\Controller\Instrumento360;

use App\Repository\Instrumento360\Instrumento360UsuariosAsignadosRepository;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\Helper;

/**
 * @OA\Tag(name="Instrumento360 Usuarios Asignados")
 */
class Instrumento360UsuariosAsignadosController extends AbstractController
{
    private $usuariosAsignadosRepository;

    public function __construct(Instrumento360UsuariosAsignadosRepository $usuariosAsignadosRepository)
    {
        $this->usuariosAsignadosRepository = $usuariosAsignadosRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/instrumento360/usuarios-asignados",
     *     summary="Crear una nueva asignación de usuario 360",
     *     operationId="usuariosAsignados360Create",
     *     tags={"Instrumento360 Usuarios Asignados"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="userId", type="integer"),
     *             @OA\Property(property="unidadUserId", type="integer"),
     *             @OA\Property(property="userEvaluadorId", type="integer"),
     *             @OA\Property(property="unidadEvaluadorId", type="integer", nullable=true),
     *             @OA\Property(property="cargoUserId", type="integer", nullable=true),
     *             @OA\Property(property="cargoEvaluadorId", type="integer", nullable=true),
     *             @OA\Property(property="instrumento360Id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asignación creada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error de validación"
     *     )
     * )
     * @Route("/api/instrumento360/usuarios-asignados", methods={"POST"})
     */
    public function create(Request $request, ValidatorInterface $validator, Helper $helper): JsonResponse
    {
        //try {
            $data = json_decode($request->getContent(), true);
            return $this->usuariosAsignadosRepository->post($data, $validator, $helper);
        //} catch (\Exception $e) {
            return new JsonResponse(['msg' => 'Error del Servidor'], 500);
        //}
    }

    /**
     * @OA\Put(
     *     path="/api/instrumento360/usuarios-asignados/{id}",
     *     summary="Actualizar una asignación de usuario 360",
     *     operationId="usuariosAsignados360Update",
     *     tags={"Instrumento360 Usuarios Asignados"},
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
     *             @OA\Property(property="userId", type="integer"),
     *             @OA\Property(property="unidadUserId", type="integer"),
     *             @OA\Property(property="userEvaluadorId", type="integer"),
     *             @OA\Property(property="unidadEvaluadorId", type="integer", nullable=true),
     *             @OA\Property(property="cargoUserId", type="integer", nullable=true),
     *             @OA\Property(property="cargoEvaluadorId", type="integer", nullable=true),
     *             @OA\Property(property="instrumento360Id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asignación actualizada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error de validación"
     *     )
     * )
     * @Route("/api/instrumento360/usuarios-asignados/{id}", methods={"PUT"})
     */
    public function update($id, Request $request, ValidatorInterface $validator, Helper $helper): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            return $this->usuariosAsignadosRepository->put($data, $id, $validator, $helper);
        } catch (\Exception $e) {
            return new JsonResponse(['msg' => 'Error del Servidor'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/instrumento360/usuarios-asignados/{id}",
     *     summary="Eliminar una asignación de usuario 360",
     *     operationId="usuariosAsignados360Delete",
     *     tags={"Instrumento360 Usuarios Asignados"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asignación eliminada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al eliminar"
     *     )
     * )
     * @Route("/api/instrumento360/usuarios-asignados/{id}", methods={"DELETE"})
     */
    public function delete($id, ValidatorInterface $validator, Helper $helper): JsonResponse
    {
        try {
            return $this->usuariosAsignadosRepository->delete($id, $validator, $helper);
        } catch (\Exception $e) {
            return new JsonResponse(['msg' => 'Error del Servidor'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/instrumento360/usuarios-asignados/{id}",
     *     summary="Obtener una asignación de usuario 360 por ID",
     *     operationId="usuariosAsignados360GetById",
     *     tags={"Instrumento360 Usuarios Asignados"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Asignación encontrada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Asignación no encontrada"
     *     )
     * )
     * @Route("/api/instrumento360/usuarios-asignados/{id}", methods={"GET"})
     */
    public function findById($id): JsonResponse
    {
        return $this->usuariosAsignadosRepository->findById($id);
    }
} 