<?php

namespace App\Controller\Instrumento360;

use App\Entity\Instrumento360\Competencia360;
use App\Repository\Instrumento360\Competencia360Repository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\Instrumento360\Competencia360Dto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;


class Competencia360Controller extends AbstractController
{


         /**
        * @Route("/api/instrumento360/competencia/pagined", methods={"POST"})
        * @OA\Post(
         * summary="Competencia 360 Pagined",
         * description="Competencia 360 Pagined",
         * operationId="competencia360pagined",
         * tags={"Instrumento 360"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="page", type="integer", format="integer", example="1"),
         *       @OA\Property(property="rowByPage", type="integer", format="integer", example="1"),
         *       @OA\Property(property="word", type="integer", format="integer", example="1")
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
    public function findList(Request $request,Competencia360Repository $competencia360Repository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $competencia360Repository
        ->findAllPage($param);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }


    
        /**
        * @Route("/api/instrumento360/competencia", methods={"POST"})
        * @OA\Post(
         * summary="Create Competencia",
         * description="Create Competencia",
         * operationId="competencia360tipocreate",
         * tags={"Instrumento 360"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Competencia",
         *    @OA\JsonContent(
         *       required={"nombre","tipo","descripcion"},
         *       @OA\Property(property="nombre", type="string", format="string", example="Buena"),
         *       @OA\Property(property="descripcion", type="string", format="string", example="1"),
         *       @OA\Property(property="tipo", type="string", format="string", example="1")

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
    public function post(Request $request,ValidatorInterface $validator,Helper $helper,Competencia360Repository $competencia360Repository): Response
    {   
        try {
            $data = json_decode($request->getContent(),true);
            return $competencia360Repository->post($data,$validator,$helper);
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


    
    /**
     *  Get Competencia by Id. 
     * tags={"Competencia 360"},
     *  description="Competencia Byid",
     * @Route("/api/instrumento360/competencia/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Competencia",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Competencia360Dto::class))
     *     )
     * )
     * @OA\Tag(name="Instrumento 360")
     * @Security(name="Bearer")
     */
    public function findById($id,Competencia360Repository $repository): JsonResponse
    {
        $data = $repository
        ->findTipoByInput($id);
         return $data;  
    }


    /**
        * @Route("/api/instrumento360/competencia/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Competencia 360",
         * description="Update Competencia 360",
         * operationId="updatecompetencias360",
         * tags={"Instrumento 360"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Competencia 360",
         *    @OA\JsonContent(
         *       required={"nombre","tipo","descripcion"},
         *       @OA\Property(property="nombre", type="string", format="string", example="Buena"),
         *       @OA\Property(property="descripcion", type="string", format="string", example="1"),
         *       @OA\Property(property="tipo", type="string", format="string", example="1")

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
    public function put($id,Request $request,ValidatorInterface $validator,Helper $helper,Competencia360Repository $repository): JsonResponse
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
     *  Get Competencia List. 
     * tags={"Instrumento 360"},
     *  description="Competencia Byid",
     * @Route("/api/instrumento360/competencia/list/all", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns List Competencia",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Competencia360Dto::class))
     *     )
     * )
     * @OA\Tag(name="Instrumento 360")
     * @Security(name="Bearer")
    */
    public function findSelect(Competencia360Repository $repository): JsonResponse
    {
        $data = $repository
        ->list();
         return $data;  
    }
}
