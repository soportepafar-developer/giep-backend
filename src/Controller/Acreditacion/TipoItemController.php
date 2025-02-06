<?php

namespace App\Controller\Acreditacion;
use App\Dto\Acreditacion\TipoItemOutputDto;
use App\Entity\Acreditacion\TipoItem;
use App\Repository\Acreditacion\TipoItemRepository;
use App\Repository\Acreditacion\ItemAcreditacionRepository;

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


class TipoItemController extends AbstractController
{



/**
        *  Get Tipo Redes by Tipo Redes Id.
        * @Route("/api/acreditacion/tipoitem/page", methods={"POST"})
        * @OA\Post(
         * summary="TipoRedes List",
         * description="TipoRedes List",
         * operationId="TipoItemlistPage",
         * tags={"Acreditaciones"},
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
         * @OA\Tag(name="Acreditaciones")
         * @Security(name="Bearer")
    */  

    public function findListAll(Request $request,TipoItemRepository $tipoitemRepository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $tipoitemRepository
        ->findAllPage($param);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }




    /**
     *  Get list. 
     * @Route("/api/acreditacion/tipoitem/list", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns TipoItem",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TipoItemOutputDto::class))
     *     )
     * )
     * @OA\Tag(name="Acreditaciones")
     * @Security(name="Bearer")
     */
    public function findList(Request $request,TipoItemRepository $repository): JsonResponse
    {
        $data = $repository
        ->findList();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }
 

    /**
        * @Route("/api/use/item/acreditacion/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Use Item Acreditacion",
         * description="Use Item Acreditacion",
         * operationId="acreditacionuseputitemtest",
         * tags={"Acreditaciones"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Use Item Acreditacion",
         *    @OA\JsonContent(
         *       required={"iditem"},
         *       @OA\Property(property="iditem", type="integer", example="1"),
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
    public function put($id,Request $request,ValidatorInterface $validator,Helper $helper,ItemAcreditacionRepository $repository): Response
    {   
        try {
            $data = json_decode($request->getContent(),true);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


            /**
        * @Route("/api/acreditacion/tipoitem", methods={"POST"})
        * @OA\Post(
         * summary="Create Tipo Item",
         * description="Create Tipo Item",
         * operationId="tipoItempost",
         * tags={"Acreditaciones"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Tipo Items",
         *    @OA\JsonContent(
         *       required={"descripcion"},
         *       @OA\Property(property="descripcion", type="string", format="string", example="Dias222"),
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
    public function post(Request $request,ValidatorInterface $validator,Helper $helper,TipoItemRepository $repository): Response
    {   
        try {
            $data = json_decode($request->getContent(),true);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


    
    /**
        * @Route("/api/acreditacion/tipoitem/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Use Item Acreditacion",
         * description="Use Item Acreditacion",
         * operationId="itemuseputitem",
         * tags={"Acreditaciones"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Use Item Acreditacion",
         *    @OA\JsonContent(
         *       required={"descripcion"},
         *       @OA\Property(property="descripcion", type="string", example="acceso"),
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
    public function putItem($id,Request $request,ValidatorInterface $validator,Helper $helper,TipoItemRepository $repository): Response
    {   
        try {
            $data = json_decode($request->getContent(),true);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


}
