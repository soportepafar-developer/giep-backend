<?php

namespace App\Controller\Evaluacion;

use App\Entity\Evaluacion\TipoInput;
use App\Form\Evaluacion\TipoInputType;
use App\Repository\Evaluacion\TipoInputRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\Evaluacion\TipoInputEvaluacionDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

class TipoInputEvaluacionController extends AbstractController
{

   /**
     *  Get list tipoinput. 
     * @Route("/api/evaluacion/tipoinpunt/list", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Tipo Input Evaluacion Evaluacion",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TipoInputEvaluacionDto::class))
     *     )
     * )
     * @OA\Tag(name="Tipo Input Evaluacion Evaluacion")
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
        * @Route("/api/evaluacion/tipoinput/pagined", methods={"POST"})
        * @OA\Post(
         * summary="Tipo Input Evaluacion Evaluacion Pagined",
         * description="Tipo Input Evaluacion Evaluacion Pagined",
         * operationId="tipoInputpaginedevaluacion",
         * tags={"Tipo Input Evaluacion Evaluacion"},
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
        * @Route("/api/evaluacion/tipoinput", methods={"POST"})
        * @OA\Post(
         * summary="Create Tipo Input Evaluacion",
         * description="Create Tipo Input Evaluacion",
         * operationId="tipoInputevaluacion",
         * tags={"Tipo Input Evaluacion Evaluacion"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Tipo Input Evaluacion Evaluacion",
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
     * tags={"Tipo Input Evaluacion Evaluacion"},
     * * description="Tipo Input Evaluacion Evaluacion Byid",
     * @Route("/api/evaluacion/tipoinput/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Tipo Input Evaluacion Evaluacion",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TipoInputEvaluacionDto::class))
     *     )
     * )
     * @OA\Tag(name="Tipo Input Evaluacion Evaluacion")
     * @Security(name="Bearer")
     */
    public function findById($id,TipoInputRepository $repository): JsonResponse
    {
        $data = $repository
        ->findTipoByInput($id);
         return $data;  
    }

    /**
        * @Route("/api/evaluacion/tipoinput/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Tipo Input Evaluacion Evaluacion",
         * description="Update Tipo Input Evaluacion Evaluacion",
         * operationId="updateTipoInputevaluacion",
         * tags={"Tipo Input Evaluacion Evaluacion"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Tipo Input Evaluacion Evaluacion",
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
        * @Route("api/evaluacion/tipoinput/{id}", methods={"DELETE"})
        * @OA\Delete(
         * summary="Delete TipoInput",
         * description="Delete TipoInput",
         * operationId="deleteinputevaluacion",
         * tags={"Tipo Input Evaluacion Evaluacion"},
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
