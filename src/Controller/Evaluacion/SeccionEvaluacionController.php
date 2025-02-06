<?php

namespace App\Controller\Evaluacion;
use App\Dto\Evaluacion\SeccionEvaluacionDto;
use App\Entity\Evaluacion\Seccion;
use App\Entity\User;
use App\Repository\Evaluacion\SeccionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;


class SeccionEvaluacionController extends AbstractController
{
     /**
        * @Route("/api/evaluacion/seccion/pagined", methods={"POST"})
        * @OA\Post(
         * summary="Seccion Pagined",
         * description="Seccion Pagined",
         * operationId="seccionpaginedevaluacion",
         * tags={"Seccion Evaluacion"},
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
     public function findAll(Request $request,SeccionRepository $repository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);

        $data = $repository
        ->findAllPage($param);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }



     /**
     *  Get List Secciones. 
     * @Route("/api/evaluacion/seccion/list", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Secciones",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=SeccionEvaluacionDto::class))
     *     )
     * )
     * @OA\Tag(name="Seccion Evaluacion")
     * @Security(name="Bearer")
     */
    public function findList(Request $request,SeccionRepository $repository): JsonResponse
    {
        $data = $repository
        ->findList();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }
 
     /**
        * @Route("/api/evaluacion/seccion", methods={"POST"})
        * @OA\Post(
         * summary="Create Seccion",
         * description="Create Seccion",
         * operationId="createSeccionevaluacion",
         * tags={"Seccion Evaluacion"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Seccion",
         *    @OA\JsonContent(
         *       @OA\Property(property="nombre", type="string", example="Datos Generales"),
         *       @OA\Property(property="orden", type="integer", example="1"),
         *       @OA\Property(property="IdInstrumentoCaptura", type="integer", example="1"),
         *       @OA\Property(property="preguntas", type="array", @OA\Items(type="array",@OA\Items()), example={{"id":"1"},{"id":"2"}}),
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
    public function post(Request $request,ValidatorInterface $validator,Helper $helper): JsonResponse
    {   
        try {
            $data = json_decode($request->getContent(),true);
            $repository = $this->getDoctrine()->getRepository(Seccion::class);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


    /**
        * @Route("/api/evaluacion/seccion/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Seccion",
         * description="Put Seccion",
         * operationId="putSeccionevaluacion",
         * tags={"Seccion Evaluacion"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Seccion",
         *    @OA\JsonContent(
         *       @OA\Property(property="nombre", type="string", example="Datos Generales"),
         *       @OA\Property(property="orden", type="integer", example="1"),
         *       @OA\Property(property="IdInstrumentoCaptura", type="integer", example="1"),
         *       @OA\Property(property="status", type="integer", example="1"),
         *       @OA\Property(property="preguntas", type="array", @OA\Items(type="array",@OA\Items()), example={{"id":"3"},{"id":"4"}}),
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
            $repository = $this->getDoctrine()->getRepository(Seccion::class);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }



         /**
        * @Route("/api/evaluacion/seccion/{id}", methods={"DELETE"})
        * @OA\Delete(
         * summary="Delete Seccion",
         * description="Delete Seccion",
         * operationId="evaluaciondeleteSeccionevaluacion",
         * tags={"Seccion Evaluacion"},
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
            $repository = $this->getDoctrine()->getRepository(Seccion::class);
            return $repository->delete($id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

}