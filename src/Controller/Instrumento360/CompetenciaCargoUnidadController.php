<?php

namespace App\Controller\Instrumento360;

use App\Entity\Instrumento360\CompetenciaCargoUnidad;
use App\Repository\Instrumento360\CompetenciaCargoUnidadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\Instrumento360\NivelDominioDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;


class CompetenciaCargoUnidadController extends AbstractController
{

         /**
        * @Route("/api/instrumento360/competencia/cargo/unidad/pagined", methods={"POST"})
        * @OA\Post(
         * summary="Competencia Cargo Unidad",
         * description="Competencia Cargo Unidad",
         * operationId="competenciacargounidadpagined",
         * tags={"Instrumento 360"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="page", type="integer", format="integer", example="1"),
         *       @OA\Property(property="rowByPage", type="integer", format="integer", example="1"),
         *       @OA\Property(property="word", type="integer", format="integer", example="1"),
         *    ),
         * ),
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
    */   
    public function findList(Request $request,CompetenciaCargoUnidadRepository $competenciaCargoUnidadRepository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $competenciaCargoUnidadRepository
        ->findAllPage($param);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

    /**
        * @Route("/api/instrumento360/competencia/cargo/unidad", methods={"POST"})
        * @OA\Post(
         * summary="Create Competencia Cargo Unidad",
         * description="Create Competencia Cargo Unidad",
         * operationId="competenciacargounidadcreate",
         * tags={"Instrumento 360"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Competencia Cargo Unidad",
         *    @OA\JsonContent(
         *       required={"cargo","dominio","competencia","unidad","prioridad"},
         *       @OA\Property(property="cargo", type="integer", format="integer", example="1"),
         *       @OA\Property(property="dominio", type="integer", format="integer", example="1"),
         *       @OA\Property(property="competencia", type="integer", format="integer", example="1"),
         *       @OA\Property(property="unidad", type="integer", format="integer", example="1"),
         *       @OA\Property(property="prioridad", type="integer", format="integer", example="1")
         *    ),
         * ),
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
    */
    public function post(Request $request,ValidatorInterface $validator,Helper $helper,CompetenciaCargoUnidadRepository $competenciaCargoUnidadRepository): Response
    {   
        try {
            $data = json_decode($request->getContent(),true);
            return $competenciaCargoUnidadRepository->post($data,$validator,$helper);
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

    /**
     *  Get Competencia Cargo Unidad by Id. 
     * tags={"Instrumento 360"},
     *  description="Competencia Cargo Unidad Byid",
     * @Route("/api/instrumento360/competencia/cargo/unidad/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Competencia Cargo Unidad",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=CompetenciaCargoUnidadDto::class))
     *     )
     * )
     * @OA\Tag(name="Instrumento 360")
     * @Security(name="Bearer")
     */
    public function findById($id,CompetenciaCargoUnidadRepository $repository): JsonResponse
    {
        $data = $repository
        ->findTipoByInput($id);
         return $data;  
    }

    /**
        * @Route("/api/instrumento360/competencia/cargo/unidad/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Competencia Cargo Unidad",
         * description="Update Competencia Cargo Unidad",
         * operationId="updatecompetenciacargounidad",
         * tags={"Instrumento 360"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Competencia Cargo Unidad",
         *    @OA\JsonContent(
         *       required={"cargo","dominio","competencia","unidad","prioridad"},
         *       @OA\Property(property="cargo", type="integer", format="integer", example="1"),
         *       @OA\Property(property="dominio", type="integer", format="integer", example="1"),
         *       @OA\Property(property="competencia", type="integer", format="integer", example="1"),
         *       @OA\Property(property="unidad", type="integer", format="integer", example="1"),
         *       @OA\Property(property="prioridad", type="integer", format="integer", example="1")
         *    ),
         * ),
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
    */
    public function put($id,Request $request,ValidatorInterface $validator,Helper $helper,CompetenciaCargoUnidadRepository $repository): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(),true);
            $em =$this->getDoctrine()->getManager();
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

    /**
     *  Get Competencia Cargo Unidad List. 
     * tags={"Instrumento 360"},
     *  description="Competencia Cargo Unidad List",
     * @Route("/api/instrumento360/competencia/cargo/unidad/list/all", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns List Competencia Cargo Unidad",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=CompetenciaCargoUnidadDto::class))
     *     )
     * )
     * @OA\Tag(name="Instrumento 360")
     * @Security(name="Bearer")
    */
    public function findSelect(CompetenciaCargoUnidadRepository $repository): JsonResponse
    {
        $data = $repository
        ->list();
         return $data;  
    }
}
