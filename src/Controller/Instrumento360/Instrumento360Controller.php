<?php

namespace App\Controller\Instrumento360;
use App\Entity\Instrumento360\Instrumento360;
use App\Repository\Instrumento360\Instrumento360Repository;
use App\Repository\Instrumento360\Instrumento360UsuariosAsignadosRepository;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;
use Nelmio\ApiDocBundle\Annotation\Security;

/**
 * @OA\Tag(name="Instrumento360")
 */
class Instrumento360Controller extends AbstractController
{
    private $evaluacionesRepository;

    
    public function __construct(Instrumento360Repository $evaluacionesRepository)
    {
        $this->evaluacionesRepository = $evaluacionesRepository;
    }

    /**
        * @Route("/api/instrumento360", methods={"POST"})
         * @OA\Post(
         * summary="Crear una nueva evaluación 360",
         * description="Crear una nueva evaluación 360",
         * operationId="instrumento360create",
         * tags={"Instrumento360"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Instrumento360",
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
    public function post(Request $request,Instrumento360Repository $instrumentorepository,ValidatorInterface $validator,Helper $helper): JsonResponse
    {   
        try {
            $data = json_decode($request->getContent(),true);
            return $instrumentorepository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


    /**
        * @Route("/api/instrumento360/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Instrumento 360",
         * description="Update Instrumento 360",
         * operationId="updateInstrumento360",
         * tags={"Instrumento360"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Instrumento 360",
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
        *  Get Instrumento360 by Id.
        * @Route("/api/instrumento360/{id}", methods={"GET"})
        * @OA\Post(
         * summary="Instrumento evaluación 360 List",
         * description="Instrumento evaluación 360 List",
         * operationId="instrumentoevaluacion360list",
         * tags={"Instrumento360"},
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
         * @OA\Tag(name="Instrumento360")
         * @Security(name="Bearer")
    */   
    public function findById($id,Request $request,Instrumento360Repository $repository): JsonResponse
    {
        $data = $repository
        ->findById($id);
         return $data;  
    }


       /**
        * @Route("/api/instrumento360/pagined", methods={"POST"})
        * @OA\Post(
         * summary="Instrumento 360 List AllPages",
         * description="Instrumento 360 List",
         * operationId="instrumento360list",
         * tags={"Instrumento360"},
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
    public function findList(Request $request): JsonResponse
    {
        
        
        $param = json_decode($request->getContent(),true);

        $instrumento360 = $this->getDoctrine()->getRepository(Instrumento360::class);
 
        $data = $instrumento360
        ->findAllPage($param);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }


    
        /**
        * @Route("/api/instrumento360/publicar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Publicar Instrumento 360",
         * description="Publicar Instrumento 360",
         * operationId="publicarInstrumento360",
         * tags={"Instrumento360"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Instrumento360",
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
            $repository = $this->getDoctrine()->getRepository(Instrumento360::class);
            return $repository->publicar($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

} 