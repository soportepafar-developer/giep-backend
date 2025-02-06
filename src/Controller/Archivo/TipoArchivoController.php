<?php

namespace App\Controller\Archivo;

use App\Entity\Archivo\TipoArchivo;
use App\Form\Archivo\TipoArchivoType;
use App\Repository\Archivo\TipoArchivoRepository;
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


class TipoArchivoController extends AbstractController
{

    /**
        * @Route("/api/tipoarchivos", methods={"POST"})
        * @OA\Post(
         * summary="Create Tipo Archivo",
         * description="Create Tipo Archivo",
         * operationId="tipoarchivo",
         * tags={"Tipo Archivos"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Tipo Archivos",
         *    @OA\JsonContent(
         *       required={"username","password"},
         *       @OA\Property(property="nombre_archivo", type="string", format="string", example="Audio"),
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
            $repository = $this->getDoctrine()->getRepository(TipoArchivo::class);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


        /**
        *  Get Status Laboral List.
        * @Route("/api/tipoarchivos/List", methods={"GET"})
        * @OA\Post(
         * summary="Tipo Archivo List",
         * description="Tipo Archivo List",
         * operationId="tipoarchivolist",
         * tags={"Tipo Archivos"},
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
         * @OA\Tag(name="Tipo Archivos")
         * @Security(name="Bearer")
    */  

    public function findList(Request $request,TipoArchivoRepository $tipoarchivosRepository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $tipoarchivosRepository
        ->findList();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }


        /**
        * @Route("/api/tipoarchivo/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Tipo Archivo",
         * description="Update Tipo Archivo",
         * operationId="updatetipoarchivo",
         * tags={"Tipo Archivos"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Tipo Archivos",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="nombre_archivo", type="string", format="string", example="Audio1"),
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
            $repository = $this->getDoctrine()->getRepository(TipoArchivo::class);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


}
