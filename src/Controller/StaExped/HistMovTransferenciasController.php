<?php

namespace App\Controller\StaExped;

use App\Entity\StaExped\HistMovTransferencias;
use App\Form\StaExped\HistMovTransferenciasType;
use App\Repository\StaExped\HistMovTransferenciasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\StaExped\HistMovTransferenciasOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;
use App\Service\FileUploader;

class HistMovTransferenciasController extends AbstractController
{

/**
     *  Get an Datos Historicos Movimientos Transferencias by Id. 
     * @Route("/api/staexped/historicosmovimientostransferencias/{Id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=HistMovTransferenciasOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Historicos Movimientos Transferencias")
     * @Security(name="Bearer")
     */
    public function getHistTransferenciaByCi($Id,HistMovTransferenciasRepository $datoshistmovtransferenciasRepository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('customer');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $datoshistmovtransferenciasRepository
        ->getHistoricosMovimientosTransferenciasByCi($Id,$em);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }
 

   /**
        * @Route("/api/staexped/historicosmovimientostransferencias", methods={"POST"})
        * @OA\Post(
         * summary="Create Historicos Movimientos Transferencias",
         * description="Create Historicos Movimientos Transferencias",
         * operationId="historicosmovimientostransferencias",
         * tags={"StaExped Historicos Movimientos Transferencias"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="id_datos_personales", type="integer", format="integer", example="4"),
         *       @OA\Property(property="id_departamento", type="integer", format="integer", example="2"),
         *       @OA\Property(property="id_area", type="integer", format="integer", example="2"),
         *       @OA\Property(property="id_region", type="integer", format="integer", example="2"),
         *       @OA\Property(property="fecha_transferencia", type="datetime", example="2022-02-19"), 
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
            $repository = $this->getDoctrine()->getRepository(HistMovTransferencias::class);
            return $repository->post($data,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

         /**
        * @Route("/api/staexped/historicosmovimientostransferencias/eliminar/{id}", methods={"DELETE"})
        * @OA\Delete(
         * summary="Delete Historicos Movimientos Transferencias",
         * description="Delete Historicos Movimientos Transferencias",
         * operationId="deletehistoricosmovimientostransferencias",
         * tags={"StaExped Historicos Movimientos Transferencias"},
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
            $repository = $this->getDoctrine()->getRepository(HistMovTransferencias::class);
            return $repository->delete($id,$validator,$helper,$em); 
        } catch (HttpException $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

       /**
         * @Route("/api/upload/historicosmovimientostransferencias", methods={"POST"})
         * @OA\Post(
         * summary="Historicos Movimientos Transferencias upload",
         * description="Historicos Movimientos Transferencias upload",
         * operationId="historicosmovimientostransferenciasload",
         * tags={"StaExped Historicos Movimientos Transferencias"},
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
    */ public function uploadTransferencias(Request $request,ValidatorInterface $validator,FileUploader $fileUploader,HistMovTransferenciasRepository $repository): JsonResponse
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
        $repository = $this->getDoctrine()->getRepository(HistMovTransferencias::class);
        if ($file) {
            $brochureFileName = $fileUploader->upload($file,"/datastaexped");

            $brochureFileName= $this->getParameter('kernel.project_dir'). '/public'.$brochureFileName;
     
            return $repository->uploadTransferencias($brochureFileName,$validator,$em,$formpermt); 
        }else{
            return new JsonResponse(['error'=>'Error Cargando Archivo'],409);  
        }   


    }




}
