<?php

namespace App\Controller\StaExped;

use App\Entity\StaExped\HistMovPromocion;
use App\Form\StaExped\HistMovPromocionType;
use App\Repository\StaExped\HistMovPromocionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\StaExped\HistMovPromocionOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;
use App\Service\FileUploader;

class HistMovPromocionController extends AbstractController
{

/**
     *  Get an Datos Permisos by Id. 
     * @Route("/api/staexped/histmovpromocion/{Id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=HistMovPromocionOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Historico Movimiento Promocion")
     * @Security(name="Bearer")
     */
    public function getReposoByCi($Id,HistMovPromocionRepository $datoshistmovpromocionRepository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('customer');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $datoshistmovpromocionRepository
        ->getHistMovPromocionByCi($Id,$em);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }
 

   /**
        * @Route("/api/staexped/histmovpromocion", methods={"POST"})
        * @OA\Post(
         * summary="Create Historico Movimiento Promocion",
         * description="Create Historico Movimiento Promocion",
         * operationId="historicomovimientopromocion",
         * tags={"StaExped Historico Movimiento Promocion"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="id_datos_personales", type="integer", format="integer", example="4"),
         *       @OA\Property(property="id_cargo", type="integer", format="integer", example="10"),
         *       @OA\Property(property="fecha_promocion", type="datetime", example="2022-02-19"), 
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
            $em = $this->getDoctrine()->getManager('customer');
            $data = json_decode($request->getContent(),true);
            $repository = $this->getDoctrine()->getRepository(HistMovPromocion::class);
            return $repository->post($data,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

         /**
        * @Route("/api/staexped/histmovpromocion/eliminar/{id}", methods={"DELETE"})
        * @OA\Delete(
         * summary="Delete Historico Movimiento Promocion",
         * description="Delete Historico Movimiento Promocion",
         * operationId="deletehistoricomovimientopromocion",
         * tags={"StaExped Historico Movimiento Promocion"},
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
            $em = $this->getDoctrine()->getManager('customer');
            $repository = $this->getDoctrine()->getRepository(HistMovPromocion::class);
            return $repository->delete($id,$validator,$helper,$em); 
        } catch (HttpException $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


           /**
         * @Route("/api/upload/histmovpromocion", methods={"POST"})
         * @OA\Post(
         * summary="Historico Movimiento Promocion upload",
         * description="Historico Movimiento Promocion upload",
         * operationId="historicomovimientopromocionload",
         * tags={"StaExped Historico Movimiento Promocion"},
         *      @OA\RequestBody(
         *         @OA\MediaType(
         *             mediaType="multipart/form-data",
         *             @OA\Schema(
         *                 @OA\Property(
         *                     description="users",
         *                     property="users",
         *                     type="string",
         *                     format="binary",
         *                 ),
         *             )
         *         )
         *     ),
         * @OA\Response(
         *    response=422,
         *    description="Photo Incorrecta",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
    */ public function uploadMovimientoPromocion(Request $request,ValidatorInterface $validator,FileUploader $fileUploader,HistMovPromocionRepository $repository): JsonResponse
    {
        $file = $request->files->get('users');
        if($file->getMimeType()!="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" AND $file->getMimeType()!="application/vnd.ms-excel"){
            return new JsonResponse(['error'=>'Formato de Archivo Incorrecto'],409);  
        }
        if($file->getMimeType()=="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"){
          $formpermt = "xlsx";              
        }else{
            $formpermt = "xls";              
        }
        $em =$this->getDoctrine()->getManager('customer');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $repository = $this->getDoctrine()->getRepository(HistMovPromocion::class);
        if ($file) {
            $brochureFileName = $fileUploader->upload($file,"/datastaexped");

            $brochureFileName= $this->getParameter('kernel.project_dir'). '/public'.$brochureFileName;
     
            return $repository->uploadMovimientoPromocion($brochureFileName,$validator,$em,$formpermt); 
        }else{
            return new JsonResponse(['error'=>'Error Cargando Archivo'],409);  
        }   


    }




}
