<?php

namespace App\Controller\StaExped;

use App\Entity\StaExped\SeguridadSaludLaboral;
use App\Form\StaExped\SeguridadSaludLaboralType;
use App\Repository\StaExped\SeguridadSaludLaboralRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Dto\StaExped\SeguridadSaludLaboralOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;
use App\Service\FileUploader;

class SeguridadSaludLaboralController extends AbstractController
{

/**
     *  Get an Datos vacaciones by Id. 
     * @Route("/api/staexped/seguridadsaludlaboral/{Id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=SeguridadSaludLaboralOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Seguridad Salud Laboral")
     * @Security(name="Bearer")
     */
    public function getReposoByCi($Id,SeguridadSaludLaboralRepository $datosSeguridadSaludLaboralRepository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('customer');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $datosSeguridadSaludLaboralRepository
        ->getSeguridadSaludLaboralByCi($Id,$em);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }
 

   /**
        * @Route("/api/staexped/seguridadsaludlaboral", methods={"POST"})
        * @OA\Post(
         * summary="Create  Seguridad Salud Laboral",
         * description="Create  Seguridad Salud Laboral",
         * operationId="SeguridadSaludLaboral",
         * tags={"StaExped Seguridad Salud Laboral"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="id_datos_personales", type="integer", format="integer", example="4"),
         *       @OA\Property(property="ruta_metro", type="integer", format="integer", example="2"),
         *       @OA\Property(property="analisis_seguro_trabajo", type="integer", format="integer", example="2"),
         *       @OA\Property(property="entrega_equipo_proteccion", type="integer", format="integer", example="2"), 
         *       @OA\Property(property="constancia_examenes_ocupacionales", type="integer", format="integer", example="2"),  
         *       @OA\Property(property="constancia_normas_seguridad", type="integer", format="integer", example="2"),  
         *       @OA\Property(property="copia_registro_delegado", type="integer", format="integer", example="2"), 
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
            $repository = $this->getDoctrine()->getRepository(SeguridadSaludLaboral::class);
            return $repository->post($data,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }



     /**
        * @Route("/api/staexped/seguridadsaludlaboral/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Seguridad Salud Laboral",
         * description="Update Seguridad Salud Laboral",
         * operationId="updateSeguridadSaludLaboral",
         * tags={"StaExped Seguridad Salud Laboral"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Seguridad Salud Laboral",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="ruta_metro", type="integer", format="integer", example="1"),
         *       @OA\Property(property="analisis_seguro_trabajo", type="integer", format="integer", example="1"),
         *       @OA\Property(property="entrega_equipo_proteccion", type="integer", format="integer", example="1"), 
         *       @OA\Property(property="constancia_examenes_ocupacionales", type="integer", format="integer", example="1"),  
         *       @OA\Property(property="constancia_normas_seguridad", type="integer", format="integer", example="1"),  
         *       @OA\Property(property="copia_registro_delegado", type="integer", format="integer", example="1"), 
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
            $repository = $this->getDoctrine()->getRepository(SeguridadSaludLaboral::class);
            return $repository->put($data,$id,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }





         /**
        * @Route("/api/staexped/seguridadsaludlaboral/eliminar/{id}", methods={"DELETE"})
        * @OA\Delete(
         * summary="Delete Seguridad Salud Laboral",
         * description="Delete  Seguridad Salud Laboral",
         * operationId="deleteseguridadsaludlaboral",
         * tags={"StaExped Seguridad Salud Laboral"},
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
            $repository = $this->getDoctrine()->getRepository(SeguridadSaludLaboral::class);
            return $repository->delete($id,$validator,$helper,$em); 
        } catch (HttpException $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


            /**
         * @Route("/api/upload/seguridadsaludlaboral", methods={"POST"})
         * @OA\Post(
         * summary="Seguridad Salud Laboral upload",
         * description="Seguridad Salud Laboral upload",
         * operationId="seguridadsaludlaboralload",
         * tags={"StaExped Seguridad Salud Laboral"},
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
    */ public function uploadStaExpedSeguridadSaludLaboral(Request $request,ValidatorInterface $validator,FileUploader $fileUploader,SeguridadSaludLaboralRepository $repository): JsonResponse
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
        $repository = $this->getDoctrine()->getRepository(SeguridadSaludLaboral::class);
        if ($file) {
            $brochureFileName = $fileUploader->upload($file,"/datastaexped");

            $brochureFileName= $this->getParameter('kernel.project_dir'). '/public'.$brochureFileName;
     
            return $repository->loadSeguridadSaludLaboral($brochureFileName,$validator,$em,$formpermt); 
        }else{
            return new JsonResponse(['error'=>'Error Cargando Archivo'],409);  
        }   


    }


}
