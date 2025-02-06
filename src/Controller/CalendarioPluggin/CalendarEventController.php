<?php

namespace App\Controller\CalendarioPluggin;

use App\Entity\CalendarioPluggin\CalendarEvent;
use App\Repository\CalendarioPluggin\CalendarEventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use  App\Service\Correo;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use App\Service\Notification;
use App\Service\Fpdfreporte;


class CalendarEventController extends AbstractController
{
    /**
        * @Route("/api/calendario/event", methods={"POST"})
        * @OA\Post(
         * summary="Create Calendario Event",
         * description="Create Calendario Event",
         * operationId="calendarioevent",
         * tags={"Calendario Event"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Calendario Event",
         *    @OA\JsonContent(
         *       required={"username","password"},
         *       @OA\Property(property="start", type="datetime", example="2023-12-30 11:02:00"), 
         *       @OA\Property(property="end", type="datetime", example="2023-12-30 12:00:00"), 
         *       @OA\Property(property="title", type="string", example="Encuentro Empresarial"),
         *       @OA\Property(property="description", type="string", example="Primer encuentro empresarial"),
         *       @OA\Property(property="acreditacion", type="integer", example="0"),
         *       @OA\Property(property="classNames", type="string", example="top-banner"),
         *       @OA\Property(property="users", type="array", @OA\Items(type="array",@OA\Items()), example={{"email":"valeriebaez.trabajo@gmail.com"},{"email":"maylygibbs807@gmail.com"}}),
         *       @OA\Property(property="accreditationItems", type="array", @OA\Items(type="array",@OA\Items()), example={{"id":"1","quantity":"1"}}),
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
    public function post(Request $request,ValidatorInterface $validator,Helper $helper,Correo $email,Notification $pushbroadcast,Fpdfreporte $Fpdfreporte): Response
    {   
        try {
            $data = json_decode($request->getContent(),true);
            $repository = $this->getDoctrine()->getRepository(CalendarEvent::class);
            return $repository->post($data,$validator,$helper,$email,$pushbroadcast,$Fpdfreporte); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


    /**
        *  Get Calendario by Range Dates.
        * @Route("/api/calendario/event/list", methods={"POST"})
        * @OA\Post(
         * summary="Get Calendario by Range Dates",
         * description="Get Calendario by Range Dates",
         * operationId="CalendarEventlist",
         * tags={"Calendario Event"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Calendario Event",
         *    @OA\JsonContent(
         *       required={"username","password"},
         *       @OA\Property(property="start", type="datetime", example="2022-09-25 23:02:02"), 
         *       @OA\Property(property="end", type="datetime", example="2022-09-26 23:02:02") 
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
         * @OA\Tag(name="Calendario Event")
         * @Security(name="Bearer")
    */   
    public function findByRange(Request $request,CalendarEventRepository $repository): JsonResponse
    {
        $data = json_decode($request->getContent(),true);

        $dataResult = $repository
        ->findByRange($data,0);
         return $dataResult;  
    }



        /**
        *  Get Calendario by Range Dates.
        * @Route("/api/calendario/event/list/acreditacion", methods={"POST"})
        * @OA\Post(
         * summary="Get Calendario by Range Dates Acreditacion",
         * description="Get Calendario by Range Dates Acreditacion",
         * operationId="CalendarEventlistAcreditacion",
         * tags={"Calendario Event"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Calendario Event Acreditacion",
         *    @OA\JsonContent(
         *       required={"username","password"},
         *       @OA\Property(property="start", type="datetime", example="2022-09-25 23:02:02"), 
         *       @OA\Property(property="end", type="datetime", example="2022-09-26 23:02:02") 
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
         * @OA\Tag(name="Calendario Event")
         * @Security(name="Bearer")
    */   
    public function findByRangeAcreditacion(Request $request,CalendarEventRepository $repository): JsonResponse
    {
        $data = json_decode($request->getContent(),true);

        $dataResult = $repository
        ->findByRange($data,1);
         return $dataResult;  
    }
    
    /**
        *  Get Acreditacion by Range Items.
        * @Route("/api/calendario/event/getone/acreditacionevent", methods={"POST"})
        * @OA\Post(
         * summary="Get Acreditacion by Range Items",
         * description="Get Calendario by Range Items",
         * operationId="CalendarGetoneAcreditacion",
         * tags={"Calendario Event"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Calendario Eventos Acreditacion",
         *    @OA\JsonContent(
         *       required={"username","password"},
         *       @OA\Property(property="ids", type="string", example="204|205|206|202"),
         *       @OA\Property(property="userId", type="string", example="48"),
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
         * @OA\Tag(name="Calendario Event")
         * @Security(name="Bearer")
    */   
    public function findByIdAcreditacionEvent(Request $request,CalendarEventRepository $repository): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $dataResult = $repository
        ->findByIdAcreditacionEvent($data);
         return $dataResult;  
    }

    /**
        * @Route("/api/calendario/event/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Edit Calendario Event",
         * description="Edit Calendario Event",
         * operationId="calendarioeventput",
         * tags={"Calendario Edit Event"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Calendario Edit Event",
         *    @OA\JsonContent(
         *       required={"start","end"},
         *       @OA\Property(property="start", type="datetime", example="2022-09-25 23:02:02"), 
         *       @OA\Property(property="end", type="datetime", example="2022-09-26 23:02:02"), 
         *       @OA\Property(property="title", type="string", example="Encuentro Empresarial"),
         *       @OA\Property(property="description", type="string", example="Primer encuentro empresarial"),
         *       @OA\Property(property="classNames", type="string", example="top-banner"),
         *       @OA\Property(property="users", type="array", @OA\Items(type="array",@OA\Items()), example={{"email":"baezgregoric@gmail.com"},{"email":"maylygibbs807@gmail.com"},{"email":"jlpadron@pafar.net"}}),
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
    public function put($id,Request $request,ValidatorInterface $validator,Helper $helper,Correo $email,Notification $pushbroadcast): Response
    {   
        try {
            $data = json_decode($request->getContent(),true);
            $repository = $this->getDoctrine()->getRepository(CalendarEvent::class);
            return $repository->put($data,$id,$validator,$helper,$email,$pushbroadcast); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

        /**
        *  Get Listado Usuarios.
        * @Route("/api/calendario/event/usuarios", methods={"GET"})
        * @OA\Post(
         * summary="Instrumento Captura List",
         * description="Instrumento Captura List",
         * operationId="instrumentocapturalist",
         * tags={"Instrumento Captura"},
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
         * @OA\Tag(name="Calendario Event")
         * @Security(name="Bearer")
    */   
    public function listUser(CalendarEventRepository $repository): JsonResponse
    {
        $data = $repository
        ->listUser();
         return $data;  
    }

    /**
        * @Route("/api/calendario/event/{id}", methods={"DELETE"})
        * @OA\Delete(
         * summary="Delete Event",
         * description="Delete Event",
         * operationId="deleteevent",
         * tags={"Calendario Event"},
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
    */
    public function delete($id,ValidatorInterface $validator,Helper $helper,Notification $pushbroadcast): Response
    {
        try {
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(CalendarEvent::class);
            return $repository->delete($id,$validator,$helper,$pushbroadcast); 
        } catch (HttpException $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


      /**
        *  Get Calendario by Id.
        * @Route("/api/calendario/event/getone/acreditacion/{id}", methods={"GET"})
        * @OA\Get(
         * summary="Calendario Event Acreditacion",
         * description="Calendario Event Acreditacion",
         * operationId="calendareventgetacreditacion",
         * tags={"Calendario Event"},
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
         * @OA\Tag(name="Calendario Event")
         * @Security(name="Bearer")
    */   
    public function findByIdAcreditacion($id,CalendarEventRepository $repository): JsonResponse
    {
        $data = $repository
        ->findByIdAcreditacion($id);
         return $data;  
    }


    /**
        *  Get Calendario by Id.
        * @Route("/api/calendario/event/getone/acreditacion/{id}/user/{iduser}", methods={"GET"})
        * @OA\Get(
         * summary="Calendario Event Acreditacion",
         * description="Calendario Event Acreditacion",
         * operationId="calendareventgetacreditacionbyuser",
         * tags={"Calendario Event"},
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
         * @OA\Tag(name="Calendario Event")
         * @Security(name="Bearer")
    */   
    public function findByIdAcreditacionAndUser($id,$iduser,CalendarEventRepository $repository): JsonResponse
    {
        $data = $repository
        ->findByIdAcreditacionAndUser($id,$iduser);
         return $data;  
    }




          /**
        *  Get Calendario by Id.
        * @Route("/api/calendario/event/getone/{id}", methods={"GET"})
        * @OA\Get(
         * summary="Calendario Event",
         * description="Calendario Event",
         * operationId="calendarevent",
         * tags={"Calendario Event"},
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
         * @OA\Tag(name="Calendario Event")
         * @Security(name="Bearer")
    */   
    public function findById($id,CalendarEventRepository $repository): JsonResponse
    {
        $data = $repository
        ->findById($id);
         return $data;  
    }



    /**
        *  Clonar Eventos by  Id.
        * @Route("/api/calendario/{id}/clonar", methods={"GET"})
        * @OA\Post(
         * summary="Clonar Evento",
         * description="Clonar Evento",
         * operationId="clonarinstrumento",
         * tags={"Calendario Event"},
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
         * @OA\Tag(name="Calendario Event")
         * @Security(name="Bearer")
    */   
    public function Clonar($id,Request $request,CalendarEventRepository $repository): JsonResponse
    {

        try {
            $data = $repository
            ->clonar($id);
            return new JsonResponse(['msg'=>'Evento Copiado '.$data],200);

        } catch (Exception $e) {
             return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }

    }    

    /**
        * @Route("/api/calendario/event/valid/user", methods={"POST"})
        * @OA\Post(
         * summary="Valid User Calendario Event",
         * description="Valid User Calendario Event",
         * operationId="validusercalendarioevent",
         * tags={"Calendario Event"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Calendario Event",
         *    @OA\JsonContent(
         *       required={"email"},
         *       @OA\Property(property="users", type="array", @OA\Items(type="array",@OA\Items()), example={"valeriebaez.trabajo@gmail.com","maylygibbs807@gmail.com"}),
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
    public function validaUsers(Request $request): Response
    {   
        try {
            $data = json_decode($request->getContent(),true);
            $repository = $this->getDoctrine()->getRepository(CalendarEvent::class);
            return $repository->validaUsers($data); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


       /**
        * @Route("/api/calendario/eventqr", methods={"POST"})
        * @OA\Post(
         * summary="Create Calendario Event Qr",
         * description="Create Calendario Event Qr",
         * operationId="calendarioeventqr",
         * tags={"Calendario Event"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Calendario Event Qr",
         *    @OA\JsonContent(
         *       required={"username","password"},
         *       @OA\Property(property="start", type="datetime", example="2023-12-30 11:02:00"), 
         *       @OA\Property(property="end", type="datetime", example="2023-12-30 12:00:00"), 
         *       @OA\Property(property="title", type="string", example="Encuentro Empresarial"),
         *       @OA\Property(property="description", type="string", example="Primer encuentro empresarial"),
         *       @OA\Property(property="users", type="array", @OA\Items(type="array",@OA\Items()), example={{"email":"maylygibbs807@gmail.com"},{"email":"baezgregoric@gmail.com"}}),
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
    public function postqr(Request $request,ValidatorInterface $validator,Helper $helper,Correo $email,Notification $pushbroadcast,Fpdfreporte $Fpdfreporte): Response
    {   
        try {
            $data = json_decode($request->getContent(),true);
            $repository = $this->getDoctrine()->getRepository(CalendarEvent::class);
            return $repository->postqr($data,$validator,$helper,$email,$pushbroadcast,$Fpdfreporte); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }    

/**
        *  Get Qr by Id.
        * @Route("/api/calendario/event/qr/{id}", methods={"GET"})
        * @OA\Get(
         * summary="Calendario Event Qr",
         * description="Calendario Event Qr",
         * operationId="calendareventqr",
         * tags={"Calendario Event Qr"},
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
         * @OA\Tag(name="Calendario Event Qr")
         * @Security(name="Bearer")
    */   
    public function findByIdQr($id,CalendarEventRepository $repository, Fpdfreporte $Fpdfreporte): JsonResponse
    {
        $data = $repository
        ->findByIdQr($id,$Fpdfreporte);
        //return new JsonResponse(['msg'=>'Listo qr generado'],500);
        return $data;  
    }


    /**
        * Post Crear Acreditaciones.
        * @Route("/api/calendario/event/print/acreditacion", methods={"POST"})
        * @OA\Post(
         * summary="Get Calendario by Range Dates Acreditacion",
         * description="Get Calendario by Range Dates Acreditacion",
         * operationId="CalendarEventPrintAcreditacion",
         * tags={"Calendario Event"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Calendario Print Acreditacion",
         *    @OA\JsonContent(
         *       required={"username","password"},
         *       @OA\Property(property="ids", type="string", example="204|206"), 
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
         * @OA\Tag(name="Calendario Event")
         * @Security(name="Bearer")
    */   
    public function printAcreditation(Request $request,CalendarEventRepository $repository): JsonResponse
    {
        $data = json_decode($request->getContent(),true);

        $dataResult = $repository
        ->printAcreditation($data,1);
         return $dataResult;  
    }
}
