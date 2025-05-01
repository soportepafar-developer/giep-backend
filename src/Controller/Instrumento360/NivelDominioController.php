<?php

namespace App\Controller\Instrumento360;

use App\Entity\Instrumento360\NivelDominio;
use App\Repository\Instrumento360\NivelDominioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\Instrumento360\NivelDominioDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;


class NivelDominioController extends AbstractController
{
     /**
        * @Route("/api/instrumento360/niveldominio/pagined", methods={"POST"})
        * @OA\Post(
         * summary="Nivel Dominio Pagined",
         * description="Nivel Dominio Pagined",
         * operationId="niveldominiopagined",
         * tags={"Instrumento 360"},
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
    public function findList(Request $request,NivelDominioRepository $nivelDominioRepository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $nivelDominioRepository
        ->findAllPage($param);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }


        /**
        * @Route("/api/instrumento360/niveldominio", methods={"POST"})
        * @OA\Post(
         * summary="Create Nivel Dominio",
         * description="Create Nivel Dominio",
         * operationId="nivelDominiocreate",
         * tags={"Instrumento 360"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Nivel Dominio",
         *    @OA\JsonContent(
         *       required={"nombre","valor","descripcion"},
         *       @OA\Property(property="nombre", type="string", format="string", example="Buena"),
         *       @OA\Property(property="valor", type="string", format="string", example="1"),
         *       @OA\Property(property="descripcion", type="string", format="string", example="Descripcion"),
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
    public function post(Request $request,ValidatorInterface $validator,Helper $helper,NivelDominioRepository $nivelDominioRepository): Response
    {   
        try {
            $data = json_decode($request->getContent(),true);
            return $nivelDominioRepository->post($data,$validator,$helper);
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


    /**
     *  Get NivelDominio by Id. 
     * tags={"Instrumento 360"},
     * * description="Nivel Dominio Byid",
     * @Route("/api/instrumento360/niveldominio/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Nivel Dominio",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=NivelDominioDto::class))
     *     )
     * )
     * @OA\Tag(name="Instrumento 360")
     * @Security(name="Bearer")
     */
    public function findById($id,NivelDominioRepository $repository): JsonResponse
    {
        $data = $repository
        ->findTipoByInput($id);
         return $data;  
    }


    /**
        * @Route("/api/instrumento360/niveldominio/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Nivel Dominio",
         * description="Update Nivel Dominio",
         * operationId="updateniveldominio",
         * tags={"Instrumento 360"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Nivel Dominio",
         *    @OA\JsonContent(
         *       required={"nombre","valor","descripcion"},
         *       @OA\Property(property="nombre", type="string", format="string", example="Buena"),
         *       @OA\Property(property="valor", type="string", format="string", example="1"),
         *       @OA\Property(property="descripcion", type="string", format="string", example="Descripcion"),
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
            $repository = $this->getDoctrine()->getRepository(NivelDominio::class);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }



    
    /**
     *  Get Dominio List. 
     * tags={"Instrumento 360"},
     *  description="Dominio List",
     * @Route("/api/instrumento360/dominio/list/all", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Dominio List",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=NivelDominioDto::class))
     *     )
     * )
     * @OA\Tag(name="Instrumento 360")
     * @Security(name="Bearer")
    */
    public function findSelect(NivelDominioRepository $repository): JsonResponse
    {
        $data = $repository
        ->list();
         return $data;  
    }
}
