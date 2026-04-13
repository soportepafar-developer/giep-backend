<?php

namespace App\Controller\Instrumento360;

use App\Entity\Instrumento360\TipoInstrumento360;
use App\Repository\Instrumento360\TipoInstrumento360Repository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\Instrumento360\TipoInstrumento360Dto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;



class TipoInstrumento360Controller extends AbstractController
{

         /**
        * @Route("/api/instrumento360/tipo/pagined", methods={"POST"})
        * @OA\Post(
         * summary="Tipo Pagined",
         * description="Tipo Pagined",
         * operationId="instrumentotipopagined",
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
    public function findList(Request $request,TipoInstrumento360Repository $tipoInstrumento360Repository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $tipoInstrumento360Repository
        ->findAllPage($param);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }


    
        /**
        * @Route("/api/instrumento360/tipo", methods={"POST"})
        * @OA\Post(
         * summary="Create Tipo",
         * description="Create Tipo",
         * operationId="instrumentio360tipocreate",
         * tags={"Instrumento 360"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Tipo",
         *    @OA\JsonContent(
         *       required={"nombre","valor","descripcion"},
         *       @OA\Property(property="nombre", type="string", format="string", example="Buena"),
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
    public function post(Request $request,ValidatorInterface $validator,Helper $helper,TipoInstrumento360Repository $tipoInstrumento360Repository): Response
    {   
        try {
            $data = json_decode($request->getContent(),true);
            return $tipoInstrumento360Repository->post($data,$validator,$helper);
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


    
    /**
     *  Get TipoInstrumento by Id. 
     * tags={"Instrumento 360"},
     *  description="Tipo Instrumento Byid",
     * @Route("/api/instrumento360/tipo/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Tipo Instrumento",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TipoInstrumento360Dto::class))
     *     )
     * )
     * @OA\Tag(name="Instrumento 360")
     * @Security(name="Bearer")
     */
    public function findById($id,TipoInstrumento360Repository $repository): JsonResponse
    {
        $data = $repository
        ->findTipoByInput($id);
         return $data;  
    }


    /**
        * @Route("/api/instrumento360/tipo/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Tipo Instrumento 360",
         * description="Update Tipo Instrumento 360",
         * operationId="updatetipoinstrumento360",
         * tags={"Instrumento 360"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Tipo Instrumento 360",
         *    @OA\JsonContent(
         *       required={"nombre","valor","descripcion"},
         *       @OA\Property(property="nombre", type="string", format="string", example="Buena"),
         *       @OA\Property(property="valor", type="string", format="string", example="1"),
         *       @OA\Property(property="statusId", type="integer", format="string", example="1")
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
    public function put($id,Request $request,ValidatorInterface $validator,Helper $helper,TipoInstrumento360Repository $repository): JsonResponse
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
     *  Get TipoInstrumento List. 
     * tags={"Instrumento 360"},
     *  description="Tipo Instrumento Byid",
     * @Route("/api/instrumento360/tipo/list/all", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns List Tipo Instrumento",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TipoInstrumento360Dto::class))
     *     )
     * )
     * @OA\Tag(name="Instrumento 360")
     * @Security(name="Bearer")
    */
    public function findSelect(TipoInstrumento360Repository $repository): JsonResponse
    {
        $data = $repository
        ->list();
         return $data;  
    }
    
}
