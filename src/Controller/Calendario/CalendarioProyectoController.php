<?php

namespace App\Controller\Calendario;

use App\Entity\Calendario\CalendarioProyecto;
use App\Form\Calendario\CalendarioProyectoType;
use App\Repository\Calendario\CalendarioProyectoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\Calendario\CalendarioProyectoOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;
use App\Service\Calculos;

class CalendarioProyectoController extends AbstractController
{

    /**
        * @Route("/api/calendarioproyecto", methods={"POST"})
        * @OA\Post(
         * summary="Create Calendario Proyecto",
         * description="Create Calendario Proyecto",
         * operationId="calendarioproyecto",
         * tags={"Calendario Proyecto"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Calendario Proyecto",
         *    @OA\JsonContent(
         *       required={"username","password"},
         *       @OA\Property(property="projectId", type="integer", format="integer", example="1"),
         *       @OA\Property(property="startDate", type="datetime", example="2023-08-22"), 
         *       @OA\Property(property="endDate", type="datetime", example="2023-08-23"), 
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
            $repository = $this->getDoctrine()->getRepository(CalendarioProyecto::class);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


    /**
        *  Get Dias no Laborables  List.
        * @Route("/api/calendarioproyecto/List", methods={"GET"})
        * @OA\Post(
         * summary="Calendario Proyecto List",
         * description="Calendario Proyecto List",
         * operationId="calendarioproyectolist",
         * tags={"Calendario Proyecto"},
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
         * @OA\Tag(name="Calendario Proyecto")
         * @Security(name="Bearer")
    */  

    public function findList(Request $request,CalendarioProyectoRepository $calendarioproyectoRepository, Calculos $Calculos): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $calendarioproyectoRepository
        ->findList($Calculos);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

            /**
        * @Route("/api/calendarioproyecto/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Calendario Proyecto",
         * description="Update Calendario Proyecto",
         * operationId="updatecalendarioproyecto",
         * tags={"Calendario Proyecto"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Calendario Proyecto",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="id_proyecto", type="integer", format="integer", example="1"),
         *       @OA\Property(property="fecha_inicio_nolaboral", type="datetime", example="2022-09-25"), 
         *       @OA\Property(property="fecha_fin_nolaboral", type="datetime", example="2022-09-29"), 
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
            $repository = $this->getDoctrine()->getRepository(CalendarioProyecto::class);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

    /**
        *  Get Calendario by Proyecto Id.
        * @Route("/api/calendarioproyecto/{id}", methods={"GET"})
        * @OA\Post(
         * summary="Calendario Proyecto List",
         * description="Calendario Proyecto List",
         * operationId="CalendarioProyectolist",
         * tags={"Calendario Proyecto"},
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
         * @OA\Tag(name="Calendario Proyecto")
         * @Security(name="Bearer")
    */   
    public function getProyectById($id,CalendarioProyectoRepository $calendarioRepository, Calculos $Calculos): JsonResponse
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $calendarioRepository
        ->getCalendarioById($id,$Calculos);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }
    
    
      /**
        * @Route("/api/recursosproyecto/deletecalendarioproyecto", methods={"PUT"})
        * @OA\Put(
         * summary="Put Calendario Proyecto",
         * description="Delete Calendario Proyecto",
         * operationId="deletecalendarioproyecto",
         * tags={"Calendario Proyecto"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Calendario Proyecto",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="idCalendarioProyecto", type="integer", format="integer", example="1"),
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
    public function putCalendarioProyecto(Request $request,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(),true);
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(CalendarioProyecto::class);
            return $repository->putCalendarioProyecto($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

}
