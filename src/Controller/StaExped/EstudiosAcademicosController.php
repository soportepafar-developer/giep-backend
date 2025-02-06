<?php

namespace App\Controller\StaExped;

use App\Entity\StaExped\EstudiosAcademicos;
use App\Dto\StaExped\EstudiosAcademicosOutPutDto;
use App\Repository\StaExped\EstudiosAcademicosRepository;
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

class EstudiosAcademicosController extends AbstractController
{
    
   /**
     *  Get an Datos Estudios Academicos by Id. 
     * @Route("/api/staexped/estudiosacademico/{Id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=EstudiosAcademicosOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Estudios Academicos")
     * @Security(name="Bearer")
     */
    public function getAcademByCi($Id,EstudiosAcademicosRepository $datosacademicosRepository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('customer');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $datosacademicosRepository
        ->getAcademByCi($Id,$em);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }
 
/**
        * @Route("/api/staexped/estudiosacademicos", methods={"POST"})
        * @OA\Post(
         * summary="Create Estudios Academicos",
         * description="Create Estudios Academicos",
         * operationId="EstudiosAcademicos",
         * tags={"StaExped Estudios Academicos"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="iddatos_personales", type="integer", format="integer", example="3"),
         *       @OA\Property(property="fecha_graduado", type="datetime", example="2022-09-15"), 
         *       @OA\Property(property="idprofesion", type="integer", format="integer", example="1"),
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
            $repository = $this->getDoctrine()->getRepository(EstudiosAcademicos::class);
            return $repository->post($data,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


        /**
        * @Route("/api/staexped/estudiosacademicos/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Estudios Academicos",
         * description="Update Estudios Academicos",
         * operationId="updateEstudiosAcademicos",
         * tags={"StaExped Estudios Academicos"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Estudios Academicos",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="fecha_graduado", type="datetime", example="2005-09-15"), 
         *       @OA\Property(property="idprofesion", type="integer", format="integer", example="2"),
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
            $repository = $this->getDoctrine()->getRepository(EstudiosAcademicos::class);
            return $repository->put($data,$id,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

     /**
        * @Route("/api/staexped/estudiosacademicos/eliminar/{id}", methods={"DELETE"})
        * @OA\Delete(
         * summary="Delete Estudios Academicos",
         * description="Delete Estudios Academicos",
         * operationId="deleteestudiosacademicos",
         * tags={"StaExped Estudios Academicos"},
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
            $repository = $this->getDoctrine()->getRepository(EstudiosAcademicos::class);
            return $repository->delete($id,$validator,$helper,$em); 
        } catch (HttpException $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

    /**
         * @Route("/api/upload/estudiosacademicos", methods={"POST"})
         * @OA\Post(
         * summary="Estudios Academicos upload",
         * description="Estudios Academicos upload",
         * operationId="estudiosacademicosload",
         * tags={"StaExped Estudios Academicos"},
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
    */ public function uploadEstudiosAcademicos(Request $request,ValidatorInterface $validator,FileUploader $fileUploader,EstudiosAcademicosRepository $repository): JsonResponse
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
        $repository = $this->getDoctrine()->getRepository(EstudiosAcademicos::class);
        if ($file) {
            $brochureFileName = $fileUploader->upload($file,"/datastaexped");

            $brochureFileName= $this->getParameter('kernel.project_dir'). '/public'.$brochureFileName;
     
            return $repository->loadEstudiosAcademicos($brochureFileName,$validator,$em,$formpermt); 
        }else{
            return new JsonResponse(['error'=>'Error Cargando Archivo'],409);  
        }   


    }
    
}
