<?php

namespace App\Controller\Proyecto;

use App\Entity\Proyecto\Spring;
use App\Form\Proyecto\SpringType;
use App\Repository\Proyecto\SpringRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\Proyecto\SpringOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

class SpringController extends AbstractController
{

/**
        *  Get Spring by Spring Id.
        * @Route("/api/spring/List", methods={"GET"})
        * @OA\Post(
         * summary="Spring List",
         * description="Spring List",
         * operationId="Springlist",
         * tags={"Spring"},
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
         * @OA\Tag(name="Spring")
         * @Security(name="Bearer")
    */  

    public function findList(Request $request,SpringRepository $springRepository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $springRepository
        ->findList();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

       /**
        * @Route("/api/spring", methods={"POST"})
        * @OA\Post(
         * summary="Create Spring",
         * description="Create Spring",
         * operationId="Spring",
         * tags={"Spring"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="idproyecto", type="integer", format="integer", example="2"), 
         *       @OA\Property(property="fechainicio", type="datetime", example="2022-09-12"), 
         *       @OA\Property(property="fechafin", type="datetime", example="2022-09-15"), 
         *       @OA\Property(property="nombre", type="string", format="integer", example="spring 1"),
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
            $repository = $this->getDoctrine()->getRepository(Spring::class);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

           /**
        * @Route("/api/spring/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Spring",
         * description="Update Spring",
         * operationId="updateSpring",
         * tags={"Spring"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Spring",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="idproyecto", type="integer", format="integer", example="1"), 
         *       @OA\Property(property="fechainicio", type="datetime", example="2022-09-12"), 
         *       @OA\Property(property="fechafin", type="datetime", example="2022-09-15"), 
         *       @OA\Property(property="nroiteracion", type="integer", format="integer", example="1"),
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
            $repository = $this->getDoctrine()->getRepository(Spring::class);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


}
