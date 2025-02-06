<?php

namespace App\Controller\Encuesta;

use App\Entity\Encuesta\Opciones;
use App\Form\Encuesta\OpcionesType;
use App\Repository\Encuesta\OpcionesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\Encuesta\OpcionesOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

class OpcionesController extends AbstractController
{

/**
        * @Route("/api/encuesta/opciones/pagined", methods={"POST"})
        * @OA\Post(
         * summary="Opciones List pagined",
         * description="Opciones pagined",
         * operationId="Opcioneslist",
         * tags={"Opciones"},
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
    public function findList(Request $request,OpcionesRepository $opciones): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $opciones
        ->findAllPage($param);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

    /**
        * @Route("/api/encuesta/opciones", methods={"POST"})
        * @OA\Post(
         * summary="Create Instrumento Captura",
         * description="Create Opciones Captura",
         * operationId="opciones",
         * tags={"Opciones"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="nombre", type="string", format="string", example="1"),
         *       @OA\Property(property="valor", type="string", format="string", example="1"),
         *       @OA\Property(property="puntos", type="integer", format="integer", example="1"),
         *       @OA\Property(property="correcta", type="integer", format="integer", example="1"),
         *       @OA\Property(property="idPregunta", type="integer", format="integer", example="2"), 
         * 
         * 
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
            $repository = $this->getDoctrine()->getRepository(Opciones::class);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

    /**
        *  Get InstrumentoCaptura by InstrumentoCaptura Id.
        * @Route("/api/encuesta/Opciones/{id}", methods={"GET"})
        * @OA\Post(
         * summary="Opciones List",
         * description="Opciones List",
         * operationId="Opcioneslist",
         * tags={"Opciones"},
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
         * @OA\Tag(name="Opciones")
         * @Security(name="Bearer")
    */   
    public function findById($id,Request $request,OpcionesRepository $repository): JsonResponse
    {
        $data = $repository
        ->findInstrumentoByCaptura($id);
         return $data;  
    }

             /**
        * @Route("/api/encuesta/Opciones/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Opciones",
         * description="Update Opciones",
         * operationId="updateOpciones",
         * tags={"Opciones"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Opciones",
         *    @OA\JsonContent(
         *       required={"nombre","descripcion"},
         *       @OA\Property(property="nombre", type="string", format="string", example="1"),
         *       @OA\Property(property="valor", type="string", format="string", example="1"),
         *       @OA\Property(property="puntos", type="integer", format="integer", example="5"),
         *       @OA\Property(property="correcta", type="integer", format="integer", example="1"),
         *       @OA\Property(property="idPregunta", type="integer", format="integer", example="2"), 
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
            $repository = $this->getDoctrine()->getRepository(Opciones::class);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


    /**
        * @Route("/api/encuesta/opciones/{id}", methods={"DELETE"})
        * @OA\Delete(
         * summary="Delete Opciones",
         * description="Delete Opciones",
         * operationId="deleteopciones",
         * tags={"Opciones"},
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
    */
    public function delete($id,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        try {
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(Opciones::class);
            return $repository->delete($id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


}
