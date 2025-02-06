<?php

namespace App\Controller\Calendario;

use App\Entity\Calendario\Statuscalendarioproyecto;
use App\Form\Calendario\StatuscalendarioproyectoType;
use App\Repository\Calendario\StatuscalendarioproyectoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\Calendario\StatuscalendarioproyectoOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

class StatuscalendarioproyectoController extends AbstractController
{

/**
        * @Route("/api/statuscalendarioproyecto", methods={"POST"})
        * @OA\Post(
         * summary="Create Status Calendario Proyecto",
         * description="Create Status Calendario Proyecto",
         * operationId="statuscalendarioproyecto",
         * tags={"Status Calendario Proyecto"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Tipo Categoria",
         *    @OA\JsonContent(
         *       required={"username","password"},
         *       @OA\Property(property="estado", type="string", format="string", example="En proceso"),
         *       @OA\Property(property="color", type="string", format="string", example="Verde"),
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
            $repository = $this->getDoctrine()->getRepository(Statuscalendarioproyecto::class);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

        /**
        *  Get Status Calendario Proyecto List.
        * @Route("/api/statuscalendarioproyecto/List", methods={"GET"})
        * @OA\Post(
         * summary="Status Calendario Proyecto List",
         * description="Status Calendario Proyecto List",
         * operationId="statuscalendarioproyecto",
         * tags={"Status Calendario Proyecto"},
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
         * @OA\Tag(name="Status Calendario Proyecto")
         * @Security(name="Bearer")
    */  

    public function findList(Request $request,StatuscalendarioproyectoRepository $statuscalendarioproyectoRepository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $statuscalendarioproyectoRepository
        ->findList();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

    /**
        * @Route("/api/statuscalendarioproyecto/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Status Calendario Proyecto",
         * description="Update Status Calendario Proyecto",
         * operationId="updateStatuscalendarioproyecto",
         * tags={"Status Calendario Proyecto"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Calendario",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="estado", type="string", format="string", example="En proceso"),
         *       @OA\Property(property="color", type="string", format="string", example="Verde"),
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
            $repository = $this->getDoctrine()->getRepository(Statuscalendarioproyecto::class);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }



}
