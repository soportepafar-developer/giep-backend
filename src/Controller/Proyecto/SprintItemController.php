<?php

namespace App\Controller\Proyecto;

use App\Entity\Proyecto\SprintItem;
use App\Entity\Proyecto\Items;
use App\Entity\Proyecto\ItemsHorasTrabajadas;
use App\Form\Proyecto\SprintItemType;
use App\Repository\Proyecto\SprintItemRepository;
use App\Repository\Proyecto\StatusisuuesRepository;
use App\Repository\Proyecto\ItemsRepository;

use App\Dto\Proyecto\SpringActivitiesOutPutDto;
use App\Repository\Proyecto\ItemsHorasTrabajadasRepository;
use App\Dto\Proyecto\ItemsDetallesOutPutDto;
    
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
class SprintItemController extends AbstractController
{


    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }


       /**
        *  Get Pais by Pais Id.
        * @Route("/api/springitem/List", methods={"GET"})
        * @OA\Post(
         * summary="Spring List",
         * description="Spring List",
         * operationId="Springlist",
         * tags={"Spring Item"},
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
         * @OA\Tag(name="Spring Item")
         * @Security(name="Bearer")
    */  

    public function findList(Request $request,SprintItemRepository $springitem): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $springitem
        ->findList();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }


    /**
        * @Route("/api/springitem/pagined", methods={"POST"})
        * @OA\Post(
         * summary="Spring Item List Pagined",
         * description="Spring Item List Pagined",
         * operationId="springitemlistall",
         * tags={"Spring Item"},
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
    public function findListAll(Request $request,SprintItemRepository $springitem): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $springitem
        ->findAllPage($param);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

/**
        * @Route("/api/actividades", methods={"POST"})
        * @OA\Post(
         * summary="Create Actividades",
         * description="Create Actividades",
         * operationId="Actividades",
         * tags={"Actividades"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="IdSpring", type="integer", format="integer", example="1"), 
         *       @OA\Property(property="IdItem", type="integer", format="integer", example="2"), 
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
            $repository = $this->getDoctrine()->getRepository(SprintItem::class);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

    /**
        * @Route("/api/tareas", methods={"POST"})
        * @OA\Post(
         * summary="Create Tareas",
         * description="Create Tareas",
         * operationId="Tareas",
         * tags={"Tareas"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="IdActividad", type="integer", format="integer", example="1"), 
         *       @OA\Property(property="IdBacklogPadre", type="integer", format="integer", example=""), 
         *       @OA\Property(property="titulo", type="string", format="string", example="Proyecto Movilnet Mariano Tarea"),  
         *       @OA\Property(property="descripcion", type="string", format="string", example="Proyecto para la automatización de los proceso Mariano Tarea"),   
         *       @OA\Property(property="horasTareas", type="integer", format="integer", example="7"),  
         *       @OA\Property(property="IdRecurso", type="integer", format="integer", example="1"), 
         *       @OA\Property(property="IdSpring", type="integer", format="integer", example="1"), 
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


    public function post_tareas(Request $request,ValidatorInterface $validator,Helper $helper): Response
    {   
        try {
            $data = json_decode($request->getContent(),true);
            $repository = $this->getDoctrine()->getRepository(Items::class);
            return $repository->post_tareas($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }
    
 
      /**
        * Get Status by Actividades Id.
        * @Route("/api/actividades/status/List", methods={"GET"})
        * @OA\Post(
         * summary="Status List",
         * description="Status List",
         * operationId="Statuslist",
         * tags={"Actividades"},
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
         * @OA\Tag(name="Actividades")
         * @Security(name="Bearer")
    */  

    public function findstatusList(Request $request,StatusisuuesRepository $statusisusues): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $statusisusues
        ->findStatusList();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }


        /**
        * @Route("/api/spring/items", methods={"POST"})
        * @OA\Post(
         * summary="Create Items",
         * description="Create Items",
         * operationId="SprinItems",
         * tags={"Tareas"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"title"},
         *       @OA\Property(property="parentId", type="integer", format="integer", example=""), 
         *       @OA\Property(property="title", type="string", format="string", example="Proyecto Movilnet Mariano Tarea"),  
         *       @OA\Property(property="typeItemId", type="integer", format="integer", example="1") ,
         *       @OA\Property(property="springId", type="integer", format="integer", example="1") ,
         *       @OA\Property(property="order", type="integer", format="integer", example="1") ,
         *       @OA\Property(property="levelBoardId", type="integer", format="integer", example="1") 
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

    public function post_items(Request $request,ValidatorInterface $validator,Helper $helper): Response
    {   
        try {
            $data = json_decode($request->getContent(),true);
            $repository = $this->getDoctrine()->getRepository(Items::class);
            return $repository->post_items($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }
    

    /**
     *  Get Activities. 
     * @Route("/api/spring/activities/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Activities",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=SpringActivitiesOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Tareas")
     * @Security(name="Bearer")
     */
    public function activities($id): JsonResponse
    {

        $repository = $this->getDoctrine()->getRepository(Items::class);
        $data = $repository
        ->activities($id,$this->params->get('urlapi'));
          return new JsonResponse($data,200);  
          
        
    }

    /**
        * @Route("/api/spring/items/actualizarposiciontareas", methods={"PUT"})
        * @OA\Put(
         * summary="Put Items",
         * description="Put Items",
         * operationId="itemsactualizar",
         * tags={"Tareas"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Items",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="id", type="integer", format="integer", example="73"),
         *       @OA\Property(property="idBacklogPadre", type="integer", format="integer", example="68"),
         *       @OA\Property(property="idnivelboardpanel", type="integer", format="integer", example="1"),
         *       @OA\Property(property="orden", type="integer", format="integer", example="3"),
         *       @OA\Property(property="previousArray", type="array", @OA\Items(type="array",@OA\Items()), example={{"idTask":71,"order":1}}), 
         *       @OA\Property(property="currentArray", type="array", @OA\Items(type="array",@OA\Items()), example={{"idTask":72,"order":1},{"idTask":74,"order":2},{"idTask":73,"order":3}}), 
         
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
    public function putItems(Request $request,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(),true);
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(Items::class);
            return $repository->putItems($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


/**
        * @Route("/api/spring/items/actualizardetallesitems", methods={"PUT"})
        * @OA\Put(
         * summary="Put Detalles Items",
         * description="Put Detalles Items",
         * operationId="itemsdetallesactualizar",
         * tags={"Tareas"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Detalles Items",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="id", type="integer", format="integer", example="112"),
         *       @OA\Property(property="id_user_id", type="integer", format="integer", example="1"),
         *       @OA\Property(property="id_backlog_padre_id", type="integer", format="integer", example="111"),
         *       @OA\Property(property="titulo", type="string", format="string", example="Tarea 3"),  
         *       @OA\Property(property="descripcion", type="string", format="string", example="descripcion del items"),  
         *       @OA\Property(property="peso", type="integer", format="integer", example="8"),
         *       @OA\Property(property="idnivelboardpanel_id", type="integer", format="integer", example="2"), 
         *       @OA\Property(property="id_typeevent_id", type="integer", format="integer", example="2"), 
         *       @OA\Property(property="comentario", type="string", format="string", example="prueba"),   
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
    public function putItemsDetalles(Request $request,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(),true);
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(Items::class);
            $resp= $repository->putItemsDetalles($data,$validator,$helper); 
            $repository = $this->getDoctrine()->getRepository(ItemsHorasTrabajadas::class);
            $repository->post_itemshorastrabajadas($data,$validator,$helper); 
            return $resp; 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

    
    /**
     *  Get DetallesItem. 
     * @Route("/api/spring/detalles/item/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Detalles",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=SpringActivitiesOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Tareas")
     * @Security(name="Bearer")
     */
    public function detallesItems($id): JsonResponse
    {

        $repository = $this->getDoctrine()->getRepository(Items::class);
        $data = $repository
        ->detalleItems($id,$this->params->get('urlapi'));
          return new JsonResponse($data,200);  
          
        
    }
    
    /**
        * @Route("/api/spring/items/actualizarmoveritems", methods={"PUT"})
        * @OA\Put(
         * summary="Put Mover Items",
         * description="Put Mover Items",
         * operationId="moveritemsactualizar",
         * tags={"Tareas"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Mover Items",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="itemsid", type="integer", format="integer", example="50"),
         *       @OA\Property(property="springfutureid", type="integer", format="integer", example="22"),
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
    public function putImoverItems(Request $request,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(),true);
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(Items::class);
            return $repository->putImoverItems($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


  /**
        * @Route("/api/spring/items/burndown", methods={"POST"})
        * @OA\Post(
         * summary="Create Items Horas Trabajadas",
         * description="Create Items Horas Trabajadas",
         * operationId="Itemshorastrabajadas",
         * tags={"Tareas"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="idSpring", type="integer", format="integer", example="39"), 
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

    public function post_itemshorastrabajadas(Request $request,ValidatorInterface $validator,Helper $helper): Response
    {   
        try {
            $data = json_decode($request->getContent(),true);
            $repository = $this->getDoctrine()->getRepository(ItemsHorasTrabajadas::class);
            //return $repository->post_itemshorastrabajadas($data,$validator,$helper); 
            return $repository->post_itemshorasestimadas($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }



}
