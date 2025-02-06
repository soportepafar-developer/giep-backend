<?php

namespace App\Controller\StaExped;
use App\Dto\StaExped\DatosPersonalesOutPutDto;
use App\Entity\StaExped\DatosPersonales;
use App\Form\StaExped\DatosPersonalesType;
use App\Repository\StaExped\DatosPersonalesRepository;
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
use App\Service\FileUploader;

class DatosPersonalesController extends AbstractController
{

   /**
     *  Get an Datos Personales by Ci. 
     * @Route("/api/staexped/personal/{ci}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=DatosPersonalesOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Personal")
     * @Security(name="Bearer")
     */
    public function getProyectByCi($ci,DatosPersonalesRepository $datospersonalesRepository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('customer');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $datospersonalesRepository
        ->getProyectByCi($ci,$em);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

  /**
        * @Route("/api/staexped/personal", methods={"POST"})
        * @OA\Post(
         * summary="Create Personal",
         * description="Create Personal",
         * operationId="Personal",
         * tags={"StaExped Personal"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="cedula", type="integer", format="integer", example="12557310"),
         *       @OA\Property(property="fecha_ingreso", type="datetime", example="2022-09-15"), 
         *       @OA\Property(property="autorizacion_ingreso", type="integer", format="integer", example="1"),
         *       @OA\Property(property="familiar_empresa", type="integer", format="integer", example="1"),
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
            $repository = $this->getDoctrine()->getRepository(DatosPersonales::class);
            return $repository->post($data,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


    /**
        * @Route("/api/staexped/personal/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Personal",
         * description="Update Personal",
         * operationId="updatePersonal",
         * tags={"StaExped Personal"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Personal",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="fecha_ingreso", type="datetime", example="2005-09-15"), 
         *       @OA\Property(property="autorizacion_ingreso", type="integer", format="integer", example="1"),
         *       @OA\Property(property="familiar_empresa", type="integer", format="integer", example="2"),
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
            $em = $this->getDoctrine()->getManager('customer');

            $data = json_decode($request->getContent(),true);
            $repository = $this->getDoctrine()->getRepository(DatosPersonales::class);
            return $repository->put($data,$id,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


     /**
         * @Route("/api/upload/datospersonales", methods={"POST"})
         * @OA\Post(
         * summary="Datospersonales upload",
         * description="Datospersonales upload",
         * operationId="datospersonalesupload",
         * tags={"StaExped Personal"},
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
    */ public function uploadDatosPersonales(Request $request,ValidatorInterface $validator,FileUploader $fileUploader,DatosPersonalesRepository $repository): JsonResponse
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
        $repository = $this->getDoctrine()->getRepository(DatosPersonales::class);
        if ($file) {
            $brochureFileName = $fileUploader->upload($file,"/datastaexped");

            $brochureFileName= $this->getParameter('kernel.project_dir'). '/public'.$brochureFileName;
     
            return $repository->loadDataPersonal($brochureFileName,$validator,$em,$formpermt); 
        }else{
            return new JsonResponse(['error'=>'Error Cargando Archivo'],409);  
        }   


    }



}
