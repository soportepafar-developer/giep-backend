<?php

namespace App\Controller\StaExped;

use App\Entity\StaExped\FideiComiso;
use App\Form\StaExped\FideiComisoType;
use App\Repository\StaExped\FideiComisoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\StaExped\FideiComisoOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;
use App\Service\FileUploader;

class FideiComisoController extends AbstractController
{


/**
     *  Get an Datos Fidei Comiso by Id. 
     * @Route("/api/staexped/fideicomiso/{Id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=FideiComisoOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Fidei Comiso")
     * @Security(name="Bearer")
     */
    public function getControlesVariosByCi($Id,FideiComisoRepository $datosFideiComisoRepository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('customer');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $datosFideiComisoRepository
        ->getFideiComisoByCi($Id,$em);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }
 

   /**
        * @Route("/api/staexped/fideicomiso", methods={"POST"})
        * @OA\Post(
         * summary="Create Fidei Comiso",
         * description="Create Fidei Comisos",
         * operationId="fideicomiso",
         * tags={"StaExped Fidei Comiso"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="id_datos_personales", type="integer", format="integer", example="4"),
         *       @OA\Property(property="const_depos_prestac_sociales", type="integer", format="integer", example="1"),
         *       @OA\Property(property="recibo_anual_intereses", type="integer", format="integer", example="2"), 
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
            $repository = $this->getDoctrine()->getRepository(FideiComiso::class);
            return $repository->post($data,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

        /**
        * @Route("/api/staexped/fideicomiso/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Fidei Comisos",
         * description="Update Fidei Comisos",
         * operationId="updateFideiComisos",
         * tags={"StaExped Fidei Comiso"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Fidei Comisos",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *    ),
         *       @OA\Property(property="const_depos_prestac_sociales", type="integer", format="integer", example="1"),
         *       @OA\Property(property="recibo_anual_intereses", type="integer", format="integer", example="2"), 
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
            $repository = $this->getDoctrine()->getRepository(FideiComiso::class);
            return $repository->put($data,$id,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }
   

         /**
        * @Route("/api/staexped/fideicomisos/eliminar/{id}", methods={"DELETE"})
        * @OA\Delete(
         * summary="Delete Fidei Comisos",
         * description="Delete Fidei Comisos",
         * operationId="deletefideicomisos",
         * tags={"StaExped Fidei Comiso"},
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
            $repository = $this->getDoctrine()->getRepository(FideiComiso::class);
            return $repository->delete($id,$validator,$helper,$em); 
        } catch (HttpException $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


        /**
         * @Route("/api/upload/fideicomisos", methods={"POST"})
         * @OA\Post(
         * summary="Fidei Comiso upload",
         * description="Fidei Comiso upload",
         * operationId="fideicomisoload",
         * tags={"StaExped Fidei Comiso"},
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
    */ public function uploadFideiComiso(Request $request,ValidatorInterface $validator,FileUploader $fileUploader,FideiComisoRepository $repository): JsonResponse
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
        $repository = $this->getDoctrine()->getRepository(FideiComiso::class);
        if ($file) {
            $brochureFileName = $fileUploader->upload($file,"/datastaexped");

            $brochureFileName= $this->getParameter('kernel.project_dir'). '/public'.$brochureFileName;
     
            return $repository->loadFideiComiso($brochureFileName,$validator,$em,$formpermt); 
        }else{
            return new JsonResponse(['error'=>'Error Cargando Archivo'],409);  
        }   


    }




}
