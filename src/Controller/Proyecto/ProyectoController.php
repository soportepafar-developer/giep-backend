<?php

namespace App\Controller\Proyecto;

use App\Entity\Proyecto\Proyecto;
use App\Form\Proyecto\ProyectoType;
use App\Repository\Proyecto\ProyectoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\Proyecto\ProyectoOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Service\Calculos;
use App\Repository\Proyecto\NivelBoardPanelRepository;
use App\Repository\Proyecto\TypeEventRepository;
class ProyectoController extends AbstractController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

  /**
        * @Route("/api/proyecto/pagined", methods={"POST"})
        * @OA\Post(
         * summary="Proyecto List Pagined",
         * description="Proyecto List Pagined",
         * operationId="Proyectolistall",
         * tags={"Proyecto"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="page", type="integer", format="integer", example="1"),
         *       @OA\Property(property="rowByPage", type="integer", format="integer", example="1"),
         *       @OA\Property(property="word", type="integer", format="integer", example="null"),
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
    public function findListAll(Request $request,ProyectoRepository $proyecto): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $proyecto
        ->findAllPage($param);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }
    
 /**
        *  Get Pais by Proyect List.
        * @Route("/api/proyecto/List", methods={"GET"})
        * @OA\Post(
         * summary="Proyecto List",
         * description="Proyecto List",
         * operationId="Proyectolist",
         * tags={"Proyecto"},
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
         * @OA\Tag(name="Proyecto")
         * @Security(name="Bearer")
    */  

    public function findList(Request $request,ProyectoRepository $proyectoRepository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $proyectoRepository
        ->findList();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

    /**
        * @Route("/api/proyecto", methods={"POST"})
        * @OA\Post(
         * summary="Create Proyecto",
         * description="Create Proyecto",
         * operationId="Proyecto",
         * tags={"Proyecto"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="nombre", type="string", format="string", example="Movilnet Mariano1"),
         *       @OA\Property(property="descripcion", type="string", format="string", example="Esta prueba de Movilnet1"),
         *       @OA\Property(property="fechainicio", type="datetime", example="2023-10-30"), 
         *       @OA\Property(property="idempresa", type="integer", format="integer", example="1"),
         *       @OA\Property(property="IdUserPmo", type="integer", format="integer", example="1"),
         *       @OA\Property(property="horaestimadas", type="integer", format="integer", example="80"),
         *       @OA\Property(property="idstatuscalendarioproyecto", type="integer", format="integer", example="2"),
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
            $repository = $this->getDoctrine()->getRepository(Proyecto::class);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


       /**
        * @Route("/api/proyecto/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Proyecto",
         * description="Update Proyecto",
         * operationId="updateProyecto",
         * tags={"Proyecto"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Proyecto",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="nombre", type="string", format="string", example="Digitel Modificado"),
         *       @OA\Property(property="fechainicio", type="datetime", example="2022-09-15"),  
         *       @OA\Property(property="idempresa", type="integer", format="integer", example="1"),
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
            $repository = $this->getDoctrine()->getRepository(Proyecto::class);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


/**
     *  Get an Proyecto by Id. 
     * @Route("/api/proyecto/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ProyectoOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Proyecto")
     * @Security(name="Bearer")
     */
    public function getProyectById($id,ProyectoRepository $proyectoRepository): JsonResponse
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $proyectoRepository
        ->getProyectById($id,$this->params->get('urlapi'));
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

    /**
     *  Get an Proyecto Pmo Recursos by Id. 
     * @Route("/api/proyecto/pmorecursos/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ProyectoOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Proyecto")
     * @Security(name="Bearer")
     */
    public function getProyectPmoRecursosById($id,ProyectoRepository $proyectoRepository, Calculos $Calculos): JsonResponse
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $proyectoRepository
        ->getProyectPmoRecursosById($id,$this->params->get('urlapi'),$Calculos);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

    /**
     *  Get an Proyecto by Id. 
     * @Route("/api/proyecto/deletePmo/{projectId}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ProyectoOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Proyecto")
     * @Security(name="Bearer")
     */
    public function getProyectPmoById($projectId,ProyectoRepository $proyectoRepository,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $proyectoRepository;
        try {
            //$repository = $this->getDoctrine()->getRepository(Proyecto::class);
            return $proyectoRepository->getProyectPmoById($projectId,$this->params->get('urlapi'),$validator,$helper);
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }

        
    }


    /**
     *  Get an Proyecto Spring by Id con actividades y tareas..
     * @Route("/api/proyecto/spring/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ProyectoOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Proyecto")
     * @Security(name="Bearer")
     */
    public function getProyectspringById($id,ProyectoRepository $proyectoRepository): JsonResponse
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $proyectoRepository
        ->getProyectspringById($id);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

        /**
        * @Route("/api/proyecto/spring/{idspring}", methods={"POST"})
        * @OA\Post(
         * summary="Spring by Id con actividades y tareas.. vs Recursos by Id",
         * description="Spring List",
         * operationId="Springlist",
         * tags={"Proyecto"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="idrecurso", type="integer", format="integer", example="1"),
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


    public function findListSpringRecursos($idspring,Request $request,ProyectoRepository $proyecto,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        $data = json_decode($request->getContent(),true);
        $data = $proyecto
        ->findSpringRecursos($data,$idspring,$validator,$helper); 
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

    /**
     *  Get an Proyecto by Id. 
     * @Route("/api/proyecto/boardpanel/{idproyecto}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ProyectoOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Proyecto")
     * @Security(name="Bearer")
     */
    public function getBoardpanelById($idproyecto,ProyectoRepository $proyectoRepository): JsonResponse
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $proyectoRepository
        ->getBoardpanelById($idproyecto);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

    /**
     *  Get an Proyecto by Id Spring. 
     * @Route("/api/proyecto/springboardpanel/{idproyecto}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ProyectoOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Proyecto")
     * @Security(name="Bearer")
     */
    public function getSpringBoardPanelById($idproyecto,ProyectoRepository $proyectoRepository): JsonResponse
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $proyectoRepository
        ->getSpringBoardPanelById($idproyecto);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

    /**
     *  Get an Proyecto by Id Actividad. 
     * @Route("/api/proyecto/boardpanelactivity/{idproye}/{idactividad}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ProyectoOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Proyecto")
     * @Security(name="Bearer")
     */
    public function getBoardpanelactividadById($idproye,$idactividad,ProyectoRepository $proyectoRepository): JsonResponse
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $proyectoRepository
        ->getBoardpanelactividadById($idproye,$idactividad);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }


    /**
     *  Get an Proyecto by Id BackLog. 
     * @Route("/api/proyecto/boardpanelbacklog/{idproye}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ProyectoOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Proyecto")
     * @Security(name="Bearer")
     */
    public function getBoardpanelbacklogById($idproye,ProyectoRepository $proyectoRepository): JsonResponse
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $proyectoRepository
        ->getBoardpanelBackLogbyId($idproye);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }


    /**
     *  Get an Proyecto by Id BackLog. 
     * @Route("/api/proyecto/boardpanelspringbacklog/{idspring}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ProyectoOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Proyecto")
     * @Security(name="Bearer")
     */
    public function getBoardpanelspringbacklogById($idspring,ProyectoRepository $proyectoRepository): JsonResponse
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $proyectoRepository
        ->getBoardpanelSpringBackLogbyId($idspring);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }
    

    /**
        * @Route("/api/proyecto/userpmo", methods={"POST"})
        * @OA\Post(
         * summary="Update User Pmo Proyecto",
         * description="Update User Pmo Proyecto",
         * operationId="UpdateUserPmoProyecto",
         * tags={"Proyecto"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="projectId", type="integer", format="integer", example="1"),
         *       @OA\Property(property="userPmoId", type="integer", format="integer", example="48"),
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

    public function postUserPmo(Request $request,ValidatorInterface $validator,Helper $helper): Response
    {   
        try {
            $data = json_decode($request->getContent(),true);
            $repository = $this->getDoctrine()->getRepository(Proyecto::class);
            return $repository->postUserPmo($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

    /**
        *  Get Pais by Proyect List.
        * @Route("/api/proyecto/nivelboard/List", methods={"GET"})
        * @OA\Post(
         * summary="Nivel Board Panel List",
         * description="Nivel Board Panel List",
         * operationId="nivelboardproyectolist",
         * tags={"Proyecto"},
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
         * @OA\Tag(name="Proyecto")
         * @Security(name="Bearer")
    */  

    public function findList2(Request $request,NivelBoardPanelRepository $NivelBoardPanelRepository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $NivelBoardPanelRepository
        ->findList2();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

  /**
        *  Get Items by Typeevent List.
        * @Route("/api/proyecto/typeitemsevent/List", methods={"GET"})
        * @OA\Post(
         * summary="Type Items Event List",
         * description="Type Items Event List",
         * operationId="typeitemseventlist",
         * tags={"Proyecto"},
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
         * @OA\Tag(name="Proyecto")
         * @Security(name="Bearer")
    */  

    public function findtypeitemsevent(Request $request,TypeEventRepository $TypeEventRepository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $TypeEventRepository
        ->findtypeitemsevent();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }
    
         /**
        * @Route("/api/proyecto/actualizarestatus/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Proyecto Estatus",
         * description="Update Proyecto Estatus",
         * operationId="updateProyectoEstatus",
         * tags={"Proyecto"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Proyecto",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="idstatuscalendarioproyecto", type="integer", format="integer", example="3"),
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
    public function putestatus($id,Request $request,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(),true);
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(Proyecto::class);
            return $repository->putestatus($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


}
