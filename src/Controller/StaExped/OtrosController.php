<?php

namespace App\Controller\StaExped;

use App\Entity\StaExped\Otros;
use App\Form\StaExped\OtrosType;
use App\Repository\StaExped\OtrosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\StaExped\OtrosOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;
use App\Service\FileUploader;

class OtrosController extends AbstractController
{

    /**
     *  Get an Datos otros by Id. 
     * @Route("/api/staexped/otros/{Id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=OtrosOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Otros")
     * @Security(name="Bearer")
     */
    public function getOtrosByCi($Id,OtrosRepository $datosOtrosRepository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('customer');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $datosOtrosRepository
        ->getOtrosByCi($Id,$em);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }
 

   /**
        * @Route("/api/staexped/otros", methods={"POST"})
        * @OA\Post(
         * summary="Create Otros",
         * description="Create Otros",
         * operationId="Otros",
         * tags={"StaExped Otros"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="id_datos_personales", type="integer", format="integer", example="4"),
         *       @OA\Property(property="expedientes_legal", type="integer", format="integer", example="2"),
         *       @OA\Property(property="motivo", type="integer", format="string", example="1"), 
         *       @OA\Property(property="curso_desarrollo_area_laboral", type="integer", format="integer", example="2"), 
         *       @OA\Property(property="profesion_orientada_area_servicio", type="string", format="string", example="creo que mas"), 
         *       @OA\Property(property="id_categoria_otros", type="integer", format="integer", example="2"),  
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
            $repository = $this->getDoctrine()->getRepository(Otros::class);
            return $repository->post($data,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


      /**
        * @Route("/api/staexped/otros/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Otros",
         * description="Update Otros",
         * operationId="updateOtros",
         * tags={"StaExped Otros"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Otros",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="expedientes_legal", type="integer", format="integer", example="2"),
         *       @OA\Property(property="motivo", type="integer", format="string", example="1"), 
         *       @OA\Property(property="curso_desarrollo_area_laboral", type="integer", format="integer", example="2"), 
         *       @OA\Property(property="profesion_orientada_area_servicio", type="string", format="string", example="creo que mas"), 
         *       @OA\Property(property="id_categoria_otros", type="integer", format="integer", example="2"),  
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
            $repository = $this->getDoctrine()->getRepository(Otros::class);
            return $repository->put($data,$id,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }



         /**
        * @Route("/api/staexped/otros/eliminar/{id}", methods={"DELETE"})
        * @OA\Delete(
         * summary="Delete Otros",
         * description="Delete Otros",
         * operationId="deleteotros",
         * tags={"StaExped Otros"},
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
            $repository = $this->getDoctrine()->getRepository(Otros::class);
            return $repository->delete($id,$validator,$helper,$em); 
        } catch (HttpException $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


            /**
         * @Route("/api/upload/otros", methods={"POST"})
         * @OA\Post(
         * summary="Otros upload",
         * description="Otros upload",
         * operationId="otrosload",
         * tags={"StaExped Otros"},
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
    */ public function uploadOtros(Request $request,ValidatorInterface $validator,FileUploader $fileUploader,OtrosRepository $repository): JsonResponse
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
        $repository = $this->getDoctrine()->getRepository(Otros::class);
        if ($file) {
            $brochureFileName = $fileUploader->upload($file,"/datastaexped");

            $brochureFileName= $this->getParameter('kernel.project_dir'). '/public'.$brochureFileName;
     
            return $repository->loadOtros($brochureFileName,$validator,$em,$formpermt); 
        }else{
            return new JsonResponse(['error'=>'Error Cargando Archivo'],409);  
        }   


    }



}
