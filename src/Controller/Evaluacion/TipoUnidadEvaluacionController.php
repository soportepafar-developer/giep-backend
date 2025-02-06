<?php

namespace App\Controller\Evaluacion;

use App\Entity\Evaluacion\TipoUnidadEvaluacion;
use App\Repository\Evaluacion\TipoUnidadEvaluacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Dto\Evaluacion\TipoUnidadEvaluacionDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

class TipoUnidadEvaluacionController extends AbstractController
{

    /**
     *  Get list tipounidad. 
     * @Route("/api/evaluacion/tipounidad/list", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Tipo Unidad Evaluacion Evaluacion",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TipoUnidadEvaluacionDto::class))
     *     )
     * )
     * @OA\Tag(name="Tipo Unidad Evaluacion Evaluacion")
     * @Security(name="Bearer")
     */
    public function findListNoPagined(Request $request,TipoUnidadRepository $tipoUnidadRepository): JsonResponse
    {
        $data = $tipoUnidadRepository
        ->findList();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }




    /**
        * @Route("/api/evaluacion/tipounidad/pagined", methods={"POST"})
        * @OA\Post(
         * summary="Tipo Unidad Evaluacion Evaluacion Pagined",
         * description="Tipo Unidad Evaluacion Evaluacion Pagined",
         * operationId="tipounidadlistevaluacion",
         * tags={"Tipo Unidad Evaluacion Evaluacion Evaluacion"},
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
    public function findList(Request $request,TipoUnidadRepository $tipoUnidadRepository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $tipoUnidadRepository
        ->findAllPage($param);
       /*  $data = $tipoUnidadRepository
        ->findList(); */
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

        /**
        * @Route("/api/evaluacion/tipounidad", methods={"POST"})
        * @OA\Post(
         * summary="Create Tipo Unidad Evaluacion Evaluacion",
         * description="Create Tipo Unidad Evaluacion Evaluacion",
         * operationId="tipoUnidadevaluacion",
         * tags={"Tipo Unidad Evaluacion Evaluacion"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Tipo Unidad Evaluacion Evaluacion",
         *    @OA\JsonContent(
         *       required={"username","password"},
         *       @OA\Property(property="nombre", type="string", format="string", example="Dias222"),
         *       @OA\Property(property="factor", type="string", format="string", example="d2"),
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
    public function post(Request $request,ValidatorInterface $validator,Helper $helper): Response
    {   
        try {
            $data = json_decode($request->getContent(),true);
            $repository = $this->getDoctrine()->getRepository(TipoUnidad::class);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

        /**
     *  Get TipoUnidad by TipoUnidad Id. 
     * tags={"Tipo Unidad Evaluacion Evaluacion"},
     * * description="Tipo Unidad Evaluacion Evaluacion Byid",
     * @Route("/api/evaluacion/tipounidad/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Tipo Unidad Evaluacion Evaluacion",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TipoUnidadEvaluacionDto::class))
     *     )
     * )
     * @OA\Tag(name="Tipo Unidad Evaluacion Evaluacion")
     * @Security(name="Bearer")
     */
    public function findById($id,TipoUnidadRepository $repository): JsonResponse
    {
        $data = $repository
        ->findTipoByUnidad($id);
         return $data;  
    }

       /**
        * @Route("/api/evaluacion/tipounidad/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Tipo Unidad Evaluacion Evaluacion",
         * description="Update Tipo Unidad Evaluacion Evaluacion",
         * operationId="updateTipoUnidadevaluacion",
         * tags={"Tipo Unidad Evaluacion Evaluacion"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Tipo Unidad Evaluacion Evaluacion",
         *    @OA\JsonContent(
         *       required={"nombre","factor"},
         *       @OA\Property(property="nombre", type="string", format="string", example="dias"),
         *       @OA\Property(property="factor", type="string", format="string", example="d"),
         *       @OA\Property(property="statusId", type="integer", format="string", example="1"),
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
    public function put($id,Request $request,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(),true);
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(TipoUnidad::class);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


    /**
        * @Route("/api/evaluacion/tipounidad/{id}", methods={"DELETE"})
        * @OA\Delete(
         * summary="Delete TipoUnidad",
         * description="Delete TipoUnidad",
         * operationId="deleteunidadevaluacion",
         * tags={"Tipo Unidad Evaluacion Evaluacion"},
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
    */
    public function delete($id,ValidatorInterface $validator,Helper $helper): Response
    {
        try {
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(TipoUnidad::class);
            return $repository->delete($id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

}
