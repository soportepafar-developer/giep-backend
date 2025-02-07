<?php

namespace App\Controller\Encuesta;

use App\Entity\Encuesta\InstrumentoCaptura;
use App\Repository\Encuesta\InstrumentoCapturaRepository;
use App\Repository\Encuesta\InstrumentoUsuarioRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\Encuesta\InstrumentoCapturaaOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

class InstrumentoCapturaController extends AbstractController
{
   /**
        * @Route("/api/encuesta/instrumentocaptura/list", methods={"POST"})
        * @OA\Post(
         * summary="Instrumento Captura List AllPages",
         * description="Instrumento Captura List",
         * operationId="instrumentocapturalist",
         * tags={"Instrumento Captura"},
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
    public function findList(Request $request,InstrumentoCapturaRepository $instrumentocaptura): JsonResponse
    {
        
        $param = json_decode($request->getContent(),true);

        $data = $instrumentocaptura
        ->findAllPage($param);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

   /**
        * @Route("/api/encuesta/instrumentocaptura/pagined", methods={"POST"})
        * @OA\Post(
         * summary="Instrumento Captura List Pagined",
         * description="Instrumento Captura Pagined",
         * operationId="instrumentocapturapagined",
         * tags={"Instrumento Captura"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="page", type="integer", format="integer", example="1"),
         *       @OA\Property(property="rowByPage", type="integer", format="integer", example="1"),
         *       @OA\Property(property="word", type="string", format="string", example="Evaluación"),
         *       @OA\Property(property="paisId", type="integer", format="integer", example="1"),
         *       @OA\Property(property="estadoId", type="integer", format="integer", example="1"),
         *       @OA\Property(property="idInstrumento", type="integer", format="integer", example="1"),
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
    public function getInstrumentosByUsuarioPage(Request $request,InstrumentoUsuarioRepository $instrumentocaptura): JsonResponse
    {
        
        $param = json_decode($request->getContent(),true);

        $data = $instrumentocaptura
        ->getInstrumentosByUsuarioPage($param);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

    


    /**
        * @Route("/api/encuesta/instrumentocaptura/list/all", methods={"POST"})
        * @OA\Post(
         * summary="Instrumento Captura List All",
         * description="Instrumento Captura List All",
         * operationId="instrumentocapturalistall",
         * tags={"Instrumento Captura"},
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
    */   
    public function findListAll(Request $request,InstrumentoCapturaRepository $instrumentocaptura): JsonResponse
    {
        

        $data = $instrumentocaptura
        ->findList();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }


    /**
        * @Route("/api/encuesta/instrumentocaptura/list/allpublicado", methods={"POST"})
        * @OA\Post(
         * summary="Instrumento Captura List All Publicados",
         * description="Instrumento Captura List All Publicados",
         * operationId="instrumentocapturalistallpublicado",
         * tags={"Instrumento Captura"},
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
    */   
    public function findListAllPublicado(Request $request,InstrumentoCapturaRepository $instrumentocaptura): JsonResponse
    {
        $data = $instrumentocaptura
        ->findListAllPublicado();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }



     /**
        * @Route("/api/encuesta/instrumentocaptura", methods={"POST"})
         * @OA\Post(
         * summary="Post Instrumento Captura",
         * description="Update Instrumento Captura",
         * operationId="postInstrumentoCaptura",
         * tags={"Instrumento Captura"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Instrumento Captura",
         *    @OA\JsonContent(
         *       required={"nombre","descripcion"},
         *       @OA\Property(property="name", type="string", format="string", example="Habilidades"),
         *       @OA\Property(property="dutation", type="integer", format="integer", example="60"),
         *       @OA\Property(property="unidad", type="integer", format="integer", example="1"),
         *       @OA\Property(property="questionsByCategory", type="integer", format="integer", example="1"),
         *       @OA\Property(property="puntosGlobales", type="integer", format="integer", example="1"),
         *       @OA\Property(property="path", type="string", example="\encuesta\test"),
         *       @OA\Property(property="expirationDate", type="datetime", example="2022-06-01"),
         *       @OA\Property(property="unitType", type="object",
         *                      @OA\Property(property="id", type="integer"),
         *                      @OA\Property(property="label", type="string"),
         *         ),
         *       @OA\Property(property="roles", type="array", @OA\Items(type="array",@OA\Items()), example={"ROLE_ADMINISTRADOR","ROLE_USER"}),
         *       @OA\Property(property="users", type="array", @OA\Items(type="array",@OA\Items()), example={{"userId":49},{"userId":50}}),
         *       @OA\Property(property="description", type="string", format="string", example="Encuesta de Evaluación de Desempeño"),
         *       @OA\Property(property="sections", type="array", @OA\Items(type="array",@OA\Items()), example={{"numberSection":1,"name":"Datos Basicos","questions":{{"order":1,"label":"Cual es su nombre","score":2,"required":1,"categoryId":1,"inputType":{{"id":2,"label":"text","score": 2}},"options":{{"value":"F","label":"Femenino","score":"2","scoreBycharges":{{"idCargo":"1","score":"0,5"}}}}}}}}),
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
            $repository = $this->getDoctrine()->getRepository(InstrumentoCaptura::class);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


    /**
        *  Get InstrumentoCaptura by InstrumentoCaptura Id.
        * @Route("/api/encuesta/instrumentocaptura/{id}", methods={"GET"})
        * @OA\Post(
         * summary="Instrumento Captura List",
         * description="Instrumento Captura List",
         * operationId="instrumentocapturalist",
         * tags={"Instrumento Captura"},
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
         * @OA\Tag(name="Instrumento Captura")
         * @Security(name="Bearer")
    */   
    public function findById($id,Request $request,InstrumentoCapturaRepository $repository): JsonResponse
    {
        $data = $repository
        ->findById($id);
         return $data;  
    }


    /**
        *  Clonar InstrumentoCaptura by InstrumentoCaptura Id.
        * @Route("/api/encuesta/instrumentocaptura/{id}/clonar", methods={"GET"})
        * @OA\Post(
         * summary="Clonar Captura",
         * description="Clonar Captura",
         * operationId="clonarinstrumento",
         * tags={"Instrumento Captura"},
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
         * @OA\Tag(name="Instrumento Captura")
         * @Security(name="Bearer")
    */   
    public function Clonar($id,Request $request,InstrumentoCapturaRepository $repository): JsonResponse
    {

        try {
            $data = $repository
            ->clonar($id);
            return new JsonResponse(['msg'=>'Instrumento Copiado '.$data],200);

        } catch (Exception $e) {
             return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }

    }

    

        /**
        * @Route("/api/encuesta/instrumentocaptura/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Instrumento Captura",
         * description="Update Instrumento Captura",
         * operationId="updateInstrumentoCaptura",
         * tags={"Instrumento Captura"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Instrumento Captura",
        *    @OA\JsonContent(
         *       required={"nombre","descripcion"},
         *       @OA\Property(property="name", type="string", format="string", example="Habilidades"),
         *       @OA\Property(property="dutation", type="integer", format="integer", example="60"),
         *       @OA\Property(property="unidad", type="integer", format="integer", example="1"),
         *       @OA\Property(property="questionsByCategory", type="integer", format="integer", example="1"),
         *       @OA\Property(property="puntosGlobales", type="integer", format="integer", example="1"),
         *       @OA\Property(property="path", type="string", example="\encuesta\test"),
         *       @OA\Property(property="expirationDate", type="datetime", example="2022-06-01"),
         *       @OA\Property(property="unitType", type="object",
         *                      @OA\Property(property="id", type="integer"),
         *                      @OA\Property(property="label", type="string"),
         *         ),
         *       @OA\Property(property="roles", type="array", @OA\Items(type="array",@OA\Items()), example={"ROLE_ADMINISTRADOR","ROLE_USER"}),
         *       @OA\Property(property="users", type="array", @OA\Items(type="array",@OA\Items()), example={{"userId":49},{"userId":50}}),
         *       @OA\Property(property="description", type="string", format="string", example="Encuesta de Evaluación de Desempeño"),
         *       @OA\Property(property="sections", type="array", @OA\Items(type="array",@OA\Items()), example={{"numberSection":1,"name":"Datos Basicos","questions":{{"order":1,"label":"Cual es su nombre","score":2,"required":1,"categoryId":1,"inputType":{{"id":2,"label":"text","score": 2}},"options":{{"value":"F","label":"Femenino","score":"2","scoreBycharges":{{"idCargo":"1","score":"0,5"}}}}}}}}),
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
            $repository = $this->getDoctrine()->getRepository(InstrumentoCaptura::class);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }



      
        /**
        * @Route("/api/encuesta/instrumentocaptura/{id}/iniciar", methods={"PUT"})
        * @OA\Put(
         * summary="Put Instrumento Captura",
         * description="Update Instrumento Captura",
         * operationId="IniciarInstrumentoCaptura",
         * tags={"Instrumento Captura"},
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
    */
    public function iniciar($id,Request $request,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(),true);
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(InstrumentoCaptura::class);
            return $repository->iniciar($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }



        /**
        * @Route("/api/encuesta/instrumentocaptura/{id}/adduser", methods={"PUT"})
        * @OA\Put(
         * summary="Add Instrumento Captura",
         * description="Update Instrumento Captura",
         * operationId="AddUserInstrumentoCaptura",
         * tags={"Instrumento Captura"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Instrumento Captura",
        *    @OA\JsonContent(
         *       required={"nombre","descripcion"},
         *       @OA\Property(property="users", type="array", @OA\Items(type="array",@OA\Items()), example={{"userId":49},{"userId":50}}),
         *       @OA\Property(property="estadoId", type="integer", format="integer", example="1"),
         *       @OA\Property(property="paisId", type="integer", format="integer", example="1"),
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
    public function addUserToInstrumento($id,Request $request,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(),true);
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(InstrumentoCaptura::class);
            return $repository->addUserToInstrumento($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

    



        /**
        * @Route("/api/encuesta/instrumentocaptura/publicar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Publicar Instrumento Captura",
         * description="Publicar Instrumento Captura",
         * operationId="publicarInstrumentoCaptura",
         * tags={"Instrumento Captura"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Instrumento Captura",
         *    @OA\JsonContent(
         *       required={"publicar"},
         *       @OA\Property(property="publicar", type="integer", format="integer", example="1"),
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
    public function publicar($id,Request $request,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(),true);
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(InstrumentoCaptura::class);
            return $repository->publicar($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }
    
        /**
        * @Route("/api/encuesta/instrumentocaptura/{id}", methods={"DELETE"})
        * @OA\Delete(
         * summary="Delete Instrumento",
         * description="Delete Instrumento",
         * operationId="deleteinstrumento",
         * tags={"Instrumento Captura"},
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
            $repository = $this->getDoctrine()->getRepository(InstrumentoCaptura::class);
            return $repository->delete($id,$validator,$helper); 
        } catch (HttpException $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


        /**
        * @Route("/api/encuesta/instrumentocaptura/{id}/orden/{order}", methods={"PUT"})
        * @OA\Put(
         * summary="Cambiar Orden Instrumento Captura",
         * description="Cambiar Orden Instrumento Captura",
         * operationId="ChangeOrderInstrumentoCaptura",
         * tags={"Instrumento Captura"},
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
    */
    public function changeOrder($id,$order,Request $request,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(),true);
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(InstrumentoCaptura::class);
            return $repository->changeOrder($data,$id,$order,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

       /**
        * @Route("/api/encuesta/instrumentocaptura/users", methods={"POST"})
        * @OA\Post(
         * summary="Instrumento Captura List AllPages",
         * description="Instrumento Captura List Users",
         * operationId="instrumentocapturalistUsers",
         * tags={"Instrumento Captura"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="page", type="integer", format="integer", example="1"),
         *       @OA\Property(property="rowByPage", type="integer", format="integer", example="1"),
         *       @OA\Property(property="word", type="integer", format="integer", example="1"),
         *       @OA\Property(property="fechaDesde", type="date", format="integer", example="2022-01-01"),
         *       @OA\Property(property="fechaHasta", type="date", format="integer", example="2022-01-31"),
         *       @OA\Property(property="instrumento", type="integer", format="integer", example="1"),
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
    public function getInstrumentosByUsuario(Request $request,InstrumentoUsuarioRepository $instrumentousuarios): JsonResponse
    {
        
        $param = json_decode($request->getContent(),true);

        $data = $instrumentousuarios
        ->getInstrumentosByUsuario($param);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }
    
    
        /**
        * @Route("/api/encuesta/instrumentocaptura/desvincularusers", methods={"POST"})
        * @OA\Post(
         * summary="Instrumento Captura Desvincular Users",
         * description="Instrumento Captura Desvincular Users",
         * operationId="instrumentocapturaDesvincularUsers",
         * tags={"Instrumento Captura"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="idinstrumento", type="date", format="integer", example="1"),
         *       @OA\Property(property="users", type="array", @OA\Items(type="array",@OA\Items()), example={{48,2260}})
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
    public function getInstrumentosDesvincularByUsuario(Request $request,InstrumentoUsuarioRepository $instrumentousuarios): JsonResponse
    {
        try {
            $param = json_decode($request->getContent(),true);
            return $instrumentousuarios->getInstrumentosDesvincularByUsuario($param);
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }



    }



}
