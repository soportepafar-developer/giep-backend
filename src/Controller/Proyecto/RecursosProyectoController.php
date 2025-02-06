<?php

namespace App\Controller\Proyecto;

use App\Entity\Proyecto\RecursosProyecto;
use App\Entity\Proyecto\CalendarioRecursosProyecto;
use App\Entity\Proyecto\CalendarioRecursosProyectoRepository;

use App\Form\Proyecto\RecursosProyectoType;
use App\Repository\Proyecto\RecursosProyectoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//use App\Dto\Proyecto\RecurososProyectoOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;
use App\Service\Calculos;

class RecursosProyectoController extends AbstractController
{

    /**
        * @Route("/api/recursosproyecto", methods={"POST"})
        * @OA\Post(
         * summary="Create Recursos Proyecto",
         * description="Create Recursos Proyecto",
         * operationId="RecursosProyecto",
         * tags={"Recursos Proyecto"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="arrayuserresorce", type="array", @OA\Items(type="array",@OA\Items()), example={{"idproyecto":14,"idrecurso":1,"horasdedicacion":150}}),
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
            $repository = $this->getDoctrine()->getRepository(RecursosProyecto::class);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


    /**
        * @Route("/api/recursosproyectoid", methods={"POST"})
        * @OA\Post(
         * summary="Create Recursos Proyecto",
         * description="Create Recursos Proyecto",
         * operationId="ProyectoRecursos",
         * tags={"Recursos Proyecto"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="projectId", type="integer", format="integer", example="1"),
         *       @OA\Property(property="userId", type="integer", format="integer", example="1"),
         *       @OA\Property(property="dedicatedHours", type="integer", format="integer", example="240"),
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

    public function postrecursosproyectoid(Request $request,ValidatorInterface $validator,Helper $helper): Response
    {   
        try {
            $data = json_decode($request->getContent(),true);
            $repository = $this->getDoctrine()->getRepository(RecursosProyecto::class);
            return $repository->postrecursosproyectoid($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

    /**
        * @Route("/api/recursosproyecto/deleteRecursos", methods={"PUT"})
        * @OA\Put(
         * summary="Put Recursos Proyecto",
         * description="Delete Recursos Proyecto",
         * operationId="deleterecursosproyecto",
         * tags={"Recursos Proyecto"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Recursos Proyecto",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="projectId", type="integer", format="integer", example="1"),
         *       @OA\Property(property="userId", type="integer", format="integer", example="251"),
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
    public function putCalendarioRecursProy(Request $request,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(),true);
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(RecursosProyecto::class);
            return $repository->putCalendarioRecursProy($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


    /**
        * @Route("/api/recursosproyecto/calendariosrecursosproyecto", methods={"POST"})
        * @OA\Post(
         * summary="Create Calendarios Recursos Proyecto",
         * description="Create Calendarios Recursos Proyecto",
         * operationId="calendariosrecursosproyecto",
         * tags={"Recursos Proyecto"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="projectId", type="integer", format="integer", example="1"),
         *       @OA\Property(property="userId", type="integer", format="integer", example="1"),
         *       @OA\Property(property="startDate", type="datetime", example="2023-09-01"), 
         *       @OA\Property(property="endDate", type="datetime", example="2023-09-05"), 
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

    public function postCalendarioRecursosProyecto(Request $request,ValidatorInterface $validator,Helper $helper): Response
    {   
        try {
            $data = json_decode($request->getContent(),true);
            $repository = $this->getDoctrine()->getRepository(CalendarioRecursosProyecto::class);
            return $repository->postCalendarioRecursosProyecto($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


/**
        * @Route("/api/recursosproyecto/calendariorecursos", methods={"Post"})
        * @OA\Post(
         * summary="Get Calendario Recursos Proyecto",
         * description="Get Calendario Recursos Proyecto",
         * operationId="calendariorecursos",
         * tags={"Recursos Proyecto"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Recursos Proyecto",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="projectId", type="integer", format="integer", example="1"),
         *       @OA\Property(property="userId", type="integer", format="integer", example="1"),
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
    public function postCalendarioRecursosProy(Request $request,ValidatorInterface $validator,Helper $helper,Calculos $Calculos): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(),true);
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(CalendarioRecursosProyecto::class);
            $data = $repository->postCalendarioRecursosProy($data,$validator,$helper,$Calculos); 
            if (!$data) {
                return new JsonResponse(['msg'=>'No existen Registros'],200);  
            }   
             return new JsonResponse($data,200);


        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

    /**
        * @Route("/api/recursosproyecto/deletecalendariorecursos", methods={"PUT"})
        * @OA\Put(
         * summary="Put Calendario Recursos Proyecto",
         * description="Delete Calendario Recursos Proyecto",
         * operationId="deletecalendariorecursosproyecto",
         * tags={"Recursos Proyecto"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Calendario Recursos Proyecto",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="calendarioRecursosId", type="integer", format="integer", example="13"),
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
    public function putCalendarioRecursosProyect(Request $request,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(),true);
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(CalendarioRecursosProyecto::class);
            return $repository->putCalendarioRecursosProyect($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


}
