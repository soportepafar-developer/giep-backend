<?php

namespace App\Controller\Evaluacion;

use App\Entity\Evaluacion\Evaluacion;
use App\Repository\Evaluacion\EvaluacionRepository;
use App\Repository\Evaluacion\EvaluacionUsuarioRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\Evaluacion\EvaluacionOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

class EvaluacionController extends AbstractController
{
   /**
        * @Route("/api/evaluacion/instrumentoevaluacion/list", methods={"POST"})
        * @OA\Post(
         * summary="Evaluacion List AllPages",
         * description="Evaluacion List",
         * operationId="evaluacionlistevaluacion",
         * tags={"Evaluacion"},
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
    public function findList(Request $request,EvaluacionRepository $evaluacion): JsonResponse
    {
        
        $param = json_decode($request->getContent(),true);

        $data = $evaluacion
        ->findAllPage($param);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

   /**
        * @Route("/api/evaluacion/instrumentoevaluacion/pagined", methods={"POST"})
        * @OA\Post(
         * summary="Evaluacion List Pagined",
         * description="Evaluacion Pagined",
         * operationId="evaluacionpaginedevaluacion",
         * tags={"Evaluacion"},
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
    public function getInstrumentosByUsuarioPage(Request $request,EvaluacionUsuarioRepository $evaluacion): JsonResponse
    {
        
        $param = json_decode($request->getContent(),true);

        $data = $evaluacion
        ->getEvaluacionByUsuarioPage($param);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

    


    /**
        * @Route("/api/evaluacion/instrumentoevaluacion/list/all", methods={"POST"})
        * @OA\Post(
         * summary="Evaluacion List All",
         * description="Evaluacion List All",
         * operationId="evaluacionlistallevaluacion",
         * tags={"Evaluacion"},
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
    */   
    public function findListAll(Request $request,EvaluacionRepository $evaluacion): JsonResponse
    {
        

        $data = $evaluacion
        ->findList();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }


    /**
        * @Route("/api/evaluacion/instrumentoevaluacion/list/allpublicados", methods={"POST"})
        * @OA\Post(
         * summary="Evaluacion List All Publicados",
         * description="Evaluacion List All Publicados",
         * operationId="evaluacionlistallevaluacionpublicados",
         * tags={"Evaluacion"},
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
    */   
    public function findListAllPublicados(Request $request,EvaluacionRepository $evaluacion): JsonResponse
    {
        $data = $evaluacion
        ->findListPublicados();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

     /**
        * @Route("/api/evaluacion/instrumentoevaluacion", methods={"POST"})
         * @OA\Post(
         * summary="Post Evaluacion",
         * description="Update Evaluacion",
         * operationId="postEvaluacionevaluacion",
         * tags={"Evaluacion"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Evaluacion",
         *    @OA\JsonContent(
         *       required={"nombre","descripcion"},
         *       @OA\Property(property="name", type="string", format="string", example="Habilidades"),
         *       @OA\Property(property="dutation", type="integer", format="integer", example="60"),
         *       @OA\Property(property="unidad", type="integer", format="integer", example="1"),
         *       @OA\Property(property="questionsByCategory", type="integer", format="integer", example="1"),
         *       @OA\Property(property="puntosGlobales", type="integer", format="integer", example="1"),
         *       @OA\Property(property="path", type="string", example="\evaluacion\test"),
         *       @OA\Property(property="expirationDate", type="datetime", example="2022-06-01"),
         *       @OA\Property(property="unitType", type="object",
         *                      @OA\Property(property="id", type="integer"),
         *                      @OA\Property(property="label", type="string"),
         *         ),
         *       @OA\Property(property="roles", type="array", @OA\Items(type="array",@OA\Items()), example={"ROLE_ADMINISTRADOR","ROLE_USER"}),
         *       @OA\Property(property="users", type="array", @OA\Items(type="array",@OA\Items()), example={{"userId":49},{"userId":50}}),
         *       @OA\Property(property="description", type="string", format="string", example="Evaluación de Desempeño"),
         *       @OA\Property(property="sections", type="array", @OA\Items(type="array",@OA\Items()), example={{"numberSection":1,"name":"Datos Basicos","questions":{{"order":1,"label":"Cual es su nombre","score":2,"required":1,"categoryId":1,"inputType":{{"id":2,"label":"text","score": 2}},"options":{{"value":"F","label":"Femenino","score":"2","scoreBycharges":{{"idCargo":"1","score":"0,5"}}}}}}}}),
         *       @OA\Property(property="evaluatorUserId", type="integer", format="integer", example="1"),
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
            $repository = $this->getDoctrine()->getRepository(Evaluacion::class);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


    /**
        *  Get Evaluacion by Evaluacion Id.
        * @Route("/api/evaluacion/instrumentoevaluacion/{id}", methods={"GET"})
        * @OA\Post(
         * summary="Evaluacion List",
         * description="Evaluacion List",
         * operationId="evaluacionlistevaluacion",
         * tags={"Evaluacion"},
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
         * @OA\Tag(name="Evaluacion")
         * @Security(name="Bearer")
    */   
    public function findById($id,Request $request,EvaluacionRepository $repository): JsonResponse
    {
        $data = $repository
        ->findById($id);
         return $data;  
    }


    /**
        *  Clonar Evaluacion by Evaluacion Id.
        * @Route("/api/evaluacion/instrumentoevaluacion/{id}/clonar", methods={"GET"})
        * @OA\Post(
         * summary="Clonar Captura",
         * description="Clonar Captura",
         * operationId="clonarevaluacionevaluacion",
         * tags={"Evaluacion"},
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
         * @OA\Tag(name="Evaluacion")
         * @Security(name="Bearer")
    */   
    public function Clonar($id,Request $request,EvaluacionRepository $repository): JsonResponse
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
        * @Route("/api/evaluacion/instrumentoevaluacion/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Evaluacion",
         * description="Update Evaluacion",
         * operationId="updateEvaluacionevaluacion",
         * tags={"Evaluacion"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Evaluacion",
        *    @OA\JsonContent(
         *       required={"nombre","descripcion"},
         *       @OA\Property(property="name", type="string", format="string", example="Habilidades"),
         *       @OA\Property(property="dutation", type="integer", format="integer", example="60"),
         *       @OA\Property(property="unidad", type="integer", format="integer", example="1"),
         *       @OA\Property(property="questionsByCategory", type="integer", format="integer", example="1"),
         *       @OA\Property(property="puntosGlobales", type="integer", format="integer", example="1"),
         *       @OA\Property(property="path", type="string", example="\evaluacion\test"),
         *       @OA\Property(property="expirationDate", type="datetime", example="2022-06-01"),
         *       @OA\Property(property="unitType", type="object",
         *                      @OA\Property(property="id", type="integer"),
         *                      @OA\Property(property="label", type="string"),
         *         ),
         *       @OA\Property(property="roles", type="array", @OA\Items(type="array",@OA\Items()), example={"ROLE_ADMINISTRADOR","ROLE_USER"}),
         *       @OA\Property(property="users", type="array", @OA\Items(type="array",@OA\Items()), example={{"userId":49},{"userId":50}}),
         *       @OA\Property(property="description", type="string", format="string", example="Evaluación de Desempeño"),
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
            $repository = $this->getDoctrine()->getRepository(Evaluacion::class);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }



      
        /**
        * @Route("/api/evaluacion/instrumentoevaluacion/{id}/iniciar", methods={"PUT"})
        * @OA\Put(
         * summary="Put Evaluacion",
         * description="Update Evaluacion",
         * operationId="IniciarEvaluacionevaluacion",
         * tags={"Evaluacion"},
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
            $repository = $this->getDoctrine()->getRepository(Evaluacion::class);
            return $repository->iniciar($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }



        /**
        * @Route("/api/evaluacion/instrumentoevaluacion/{id}/adduser", methods={"PUT"})
        * @OA\Put(
         * summary="Add Evaluacion",
         * description="Update Evaluacion",
         * operationId="AddUserEvaluacionevaluacion",
         * tags={"Evaluacion"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Evaluacion",
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
    public function addUserToEvaluacion($id,Request $request,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(),true);
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(Evaluacion::class);
            return $repository->addUserToEvaluacion($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

    



        /**
        * @Route("/api/evaluacion/instrumentoevaluacion/publicar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Publicar Evaluacion",
         * description="Publicar Evaluacion",
         * operationId="publicarEvaluacionevaluacion",
         * tags={"Evaluacion"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Evaluacion",
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
            $repository = $this->getDoctrine()->getRepository(Evaluacion::class);
            return $repository->publicar($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }
    
        /**
        * @Route("/api/evaluacion/instrumentoevaluacion/{id}", methods={"DELETE"})
        * @OA\Delete(
         * summary="Delete Instrumento",
         * description="Delete Instrumento",
         * operationId="deleteevaluacionevaluacion",
         * tags={"Evaluacion"},
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
            $repository = $this->getDoctrine()->getRepository(Evaluacion::class);
            return $repository->delete($id,$validator,$helper); 
        } catch (HttpException $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


        /**
        * @Route("/api/evaluacion/instrumentoevaluacion/{id}/orden/{order}", methods={"PUT"})
        * @OA\Put(
         * summary="Cambiar Orden Evaluacion",
         * description="Cambiar Orden Evaluacion",
         * operationId="ChangeOrderEvaluacionevaluacion",
         * tags={"Evaluacion"},
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
            $repository = $this->getDoctrine()->getRepository(Evaluacion::class);
            return $repository->changeOrder($data,$id,$order,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

       /**
        * @Route("/api/evaluacion/instrumentoevaluacion/users", methods={"POST"})
        * @OA\Post(
         * summary="Evaluacion List AllPages",
         * description="Evaluacion List Users",
         * operationId="evaluacionlistUsersevaluacion",
         * tags={"Evaluacion"},
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
         *       @OA\Property(property="evaluacion", type="integer", format="integer", example="1"),
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
    public function getInstrumentosByUsuario(Request $request,EvaluacionUsuarioRepository $evaluacionusuarios): JsonResponse
    {
        
        $param = json_decode($request->getContent(),true);

        $data = $evaluacionusuarios
        ->getInstrumentosByUsuario($param);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }


       /**
        * @Route("/api/evaluacion/instrumentoevaluacion/list/instructor", methods={"POST"})
        * @OA\Post(
         * summary="Evaluacion List AllPages Instructor",
         * description="Evaluacion List Instructor",
         * operationId="evaluacionlistevaluacionInstructor",
         * tags={"Evaluacion"},
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
    public function findListByInstructor(Request $request,EvaluacionRepository $evaluacion): JsonResponse
    {
        
        $param = json_decode($request->getContent(),true);

        $data = $evaluacion
        ->findListByInstructor($param);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

    /**
        * @Route("/api/encuesta/instrumentoevaluacion/desvincularusers", methods={"POST"})
        * @OA\Post(
         * summary="Instrumento Evaluacion Desvincular Users",
         * description="Instrumento Evaluacion Desvincular Users",
         * operationId="instrumentoEvaluacionDesvincularUsers",
         * tags={"Evaluacion"},
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
    public function getInstrumentosDesvincularByUsuario(Request $request,EvaluacionUsuarioRepository $evaluacionusuarios): JsonResponse
    {
        try {
            $param = json_decode($request->getContent(),true);
            return $evaluacionusuarios->getEvaluacionDesvincularByUsuario($param);
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }



    }


}