<?php

namespace App\Controller\Encuesta;

use App\Entity\Encuesta\TipoInput;
use App\Form\Encuesta\TipoInputType;
use App\Repository\Encuesta\TipoInputRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\Encuesta\TipoInputOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

class TipoInputController extends AbstractController
{

   /**
     *  Get list tipoinput. 
     * @Route("/api/encuesta/tipoinpunt/list", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Tipo Input",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TipoInputOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Tipo Input")
     * @Security(name="Bearer")
     */
    public function findListNoPagined(Request $request,TipoInputRepository $tipoInputRepository): JsonResponse
    {
        $data = $tipoInputRepository
        ->findList();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }




      /**
        * @Route("/api/encuesta/tipoinput/pagined", methods={"POST"})
        * @OA\Post(
         * summary="Tipo Input Pagined",
         * description="Tipo Input Pagined",
         * operationId="tipoInputpagined",
         * tags={"Tipo Input"},
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
    public function findList(Request $request,TipoInputRepository $tipoInputRepository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $tipoInputRepository
        ->findAllPage($param);
        /* $data = $tipoInputRepository
        ->findList(); */
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }
  
        /**
        * @Route("/api/encuesta/tipoinput", methods={"POST"})
        * @OA\Post(
         * summary="Create Tipo Input",
         * description="Create Tipo Input",
         * operationId="tipoInput",
         * tags={"Tipo Input"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Tipo Input",
         *    @OA\JsonContent(
         *       required={"username","password"},
         *       @OA\Property(property="nombre", type="string", format="string", example="Select"),
         *       @OA\Property(property="seleccionMultiple", type="integer", format="string", example="1"),
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
            $repository = $this->getDoctrine()->getRepository(TipoInput::class);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

     /**
     *  Get TipoInput by TipoInput Id. 
     * tags={"Tipo Input"},
     * * description="Tipo Input Byid",
     * @Route("/api/encuesta/tipoinput/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Tipo Input",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TipoInputOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Tipo Input")
     * @Security(name="Bearer")
     */
    public function findById($id,TipoInputRepository $repository): JsonResponse
    {
        $data = $repository
        ->findTipoByInput($id);
         return $data;  
    }

    /**
        * @Route("/api/encuesta/tipoinput/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Tipo Input",
         * description="Update Tipo Input",
         * operationId="updateTipoInput",
         * tags={"Tipo Input"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Tipo Input",
         *    @OA\JsonContent(
         *       required={"nombre","seleccionMultiple"},
         *       @OA\Property(property="nombre", type="string", format="string", example="select323323"),
         *       @OA\Property(property="seleccionMultiple", type="string", format="string", example="1"),
         *       @OA\Property(property="statusId", type="integer", format="string", example="1")
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
            $repository = $this->getDoctrine()->getRepository(TipoInput::class);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


    /**
        * @Route("api/encuesta/tipoinput/{id}", methods={"DELETE"})
        * @OA\Delete(
         * summary="Delete TipoInput",
         * description="Delete TipoInput",
         * operationId="deleteinput",
         * tags={"Tipo Input"},
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
            $repository = $this->getDoctrine()->getRepository(TipoInput::class);
            return $repository->delete($id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }
}
