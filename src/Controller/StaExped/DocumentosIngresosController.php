<?php

namespace App\Controller\StaExped;

use App\Entity\StaExped\DocumentosIngresos;
use App\Form\StaExped\DocumentosIngresosType;
use App\Repository\StaExped\DocumentosIngresosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\StaExped\DocumentosIngresosOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;
use App\Service\FileUploader;

class DocumentosIngresosController extends AbstractController
{

/**
     *  Get an Datos documentos ingresos by Id. 
     * @Route("/api/staexped/documentosingresos/{Id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=DocumentosIngresosOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Documentos Ingresos")
     * @Security(name="Bearer")
     */
    public function getDocumentosIngresosByCi($Id,DocumentosIngresosRepository $datosDocumentosIngresosRepository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('customer');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $datosDocumentosIngresosRepository
        ->getDocumentosIngresosByCi($Id,$em);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }
 

   /**
        * @Route("/api/staexped/documentosingresos", methods={"POST"})
        * @OA\Post(
         * summary="Create Documentos Ingresos",
         * description="Create Documentos Ingresos",
         * operationId="documentosingresos",
         * tags={"StaExped Documentos Ingresos"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="id_datos_personales", type="integer", format="integer", example="4"),
         *       @OA\Property(property="solicitud_empleo", type="integer", format="integer", example="2"),
         *       @OA\Property(property="sintesis_curricular", type="integer", format="integer", example="2"),
         *       @OA\Property(property="copia_cedula", type="integer", format="integer", example="2"), 
         *       @OA\Property(property="constancia_trabajo", type="integer", format="integer", example="2"),  
         *       @OA\Property(property="reg_informacion_fiscal", type="integer", format="integer", example="2"),  
         *       @OA\Property(property="verificacion_ref_laborales", type="integer", format="integer", example="2"), 
         *       @OA\Property(property="certificacion_declaracion_jurada", type="integer", format="integer", example="2"), 
         *       @OA\Property(property="licencia", type="integer", format="integer", example="2"), 
         *       @OA\Property(property="certificado_medico", type="integer", format="integer", example="2"), 
         *       @OA\Property(property="punto_cuenta", type="integer", format="integer", example="2"),  
         *       @OA\Property(property="poseer_titulo", type="integer", format="integer", example="2"),  
         *       @OA\Property(property="descripcion_cargo", type="integer", format="integer", example="2"),  
         *       @OA\Property(property="id_confidencialidad", type="integer", format="integer", example="2"),  
         *       @OA\Property(property="id_cargo", type="integer", format="integer", example="10"),  
         *       @OA\Property(property="id_departamento", type="integer", format="integer", example="2"),  
         *       @OA\Property(property="id_area", type="integer", format="integer", example="2"),  
         *       @OA\Property(property="id_region", type="integer", format="integer", example="2"),  
         * 
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
            $repository = $this->getDoctrine()->getRepository(DocumentosIngresos::class);
            return $repository->post($data,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }



       /**
        * @Route("/api/staexped/documentosingresos/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Documentos Ingresos",
         * description="Update Documentos Ingresos",
         * operationId="updateDocumentosingresos",
         * tags={"StaExped Documentos Ingresos"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Documentos Ingresos",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *    ),
         *       @OA\Property(property="solicitud_empleo", type="integer", format="integer", example="2"),
         *       @OA\Property(property="sintesis_curricular", type="integer", format="integer", example="2"),
         *       @OA\Property(property="copia_cedula", type="integer", format="integer", example="2"), 
         *       @OA\Property(property="constancia_trabajo", type="integer", format="integer", example="2"),  
         *       @OA\Property(property="reg_informacion_fiscal", type="integer", format="integer", example="2"),  
         *       @OA\Property(property="verificacion_ref_laborales", type="integer", format="integer", example="2"), 
         *       @OA\Property(property="certificacion_declaracion_jurada", type="integer", format="integer", example="2"), 
         *       @OA\Property(property="licencia", type="integer", format="integer", example="2"), 
         *       @OA\Property(property="certificado_medico", type="integer", format="integer", example="2"), 
         *       @OA\Property(property="punto_cuenta", type="integer", format="integer", example="2"),  
         *       @OA\Property(property="poseer_titulo", type="integer", format="integer", example="2"),  
         *       @OA\Property(property="descripcion_cargo", type="integer", format="integer", example="2"),  
         *       @OA\Property(property="id_confidencialidad", type="integer", format="integer", example="2"),  
         *       @OA\Property(property="id_cargo", type="integer", format="integer", example="10"),  
         *       @OA\Property(property="id_departamento", type="integer", format="integer", example="2"),  
         *       @OA\Property(property="id_area", type="integer", format="integer", example="2"),  
         *       @OA\Property(property="id_region", type="integer", format="integer", example="2"),  
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
            $repository = $this->getDoctrine()->getRepository(DocumentosIngresos::class);
            return $repository->put($data,$id,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


         /**
        * @Route("/api/staexped/documentosingresos/eliminar/{id}", methods={"DELETE"})
        * @OA\Delete(
         * summary="Delete Documentos Ingresos",
         * description="Delete Documentos Ingresos",
         * operationId="deletedocumentosingresos",
         * tags={"StaExped Documentos Ingresos"},
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
            $repository = $this->getDoctrine()->getRepository(DocumentosIngresos::class);
            return $repository->delete($id,$validator,$helper,$em); 
        } catch (HttpException $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

    /**
         * @Route("/api/upload/documentosingresos", methods={"POST"})
         * @OA\Post(
         * summary="Documentos Ingresos upload",
         * description="Documentos Ingresos upload",
         * operationId="documentosingresosload",
         * tags={"StaExped Documentos Ingresos"},
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
    */ public function uploadDocumentosIngresos(Request $request,ValidatorInterface $validator,FileUploader $fileUploader,DocumentosIngresosRepository $repository): JsonResponse
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
        $repository = $this->getDoctrine()->getRepository(DocumentosIngresos::class);
        if ($file) {
            $brochureFileName = $fileUploader->upload($file,"/datastaexped");

            $brochureFileName= $this->getParameter('kernel.project_dir'). '/public'.$brochureFileName;
     
            return $repository->loadDocumentosIngresos($brochureFileName,$validator,$em,$formpermt); 
        }else{
            return new JsonResponse(['error'=>'Error Cargando Archivo'],409);  
        }   


    }


}
