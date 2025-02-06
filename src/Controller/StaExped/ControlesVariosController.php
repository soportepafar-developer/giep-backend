<?php

namespace App\Controller\StaExped;

use App\Entity\StaExped\ControlesVarios;
use App\Form\StaExped\ControlesVariosType;
use App\Repository\StaExped\ControlesVariosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\StaExped\ControlesVariosOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;
use App\Service\FileUploader;

class ControlesVariosController extends AbstractController
{

    /**
     *  Get an Datos controles varios by Id. 
     * @Route("/api/staexped/controlesvarios/{Id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ControlesVariosOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Controles Varios")
     * @Security(name="Bearer")
     */
    public function getControlesVariosByCi($Id,ControlesVariosRepository $datosControlesVariosRepository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('customer');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $datosControlesVariosRepository
        ->getControlesVariosByCi($Id,$em);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }
 

   /**
        * @Route("/api/staexped/controlesvarios", methods={"POST"})
        * @OA\Post(
         * summary="Create controles varios",
         * description="Create controles varios",
         * operationId="controlesvarios",
         * tags={"StaExped Controles Varios"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="id_datos_personales", type="integer", format="integer", example="4"),
         *       @OA\Property(property="normas_internas", type="integer", format="integer", example="1"),
         *       @OA\Property(property="inscrito_ivss", type="integer", format="integer", example="2"), 
         *       @OA\Property(property="forma_ari", type="integer", format="integer", example="1"),  
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
            $repository = $this->getDoctrine()->getRepository(ControlesVarios::class);
            return $repository->post($data,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


    /**
        * @Route("/api/staexped/controlesvarios/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Controles Varios",
         * description="Update Controles Varios",
         * operationId="updateControlesVarios",
         * tags={"StaExped Controles Varios"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Controles Varios",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *    ),
 *       @OA\Property(property="normas_internas", type="integer", format="integer", example="1"),
         *       @OA\Property(property="inscrito_ivss", type="integer", format="integer", example="2"), 
         *       @OA\Property(property="forma_ari", type="integer", format="integer", example="1"),          
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
            $repository = $this->getDoctrine()->getRepository(ControlesVarios::class);
            return $repository->put($data,$id,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }



         /**
        * @Route("/api/staexped/controlesvarios/eliminar/{id}", methods={"DELETE"})
        * @OA\Delete(
         * summary="Delete Controles Varios",
         * description="Delete Controles Varios",
         * operationId="deletecontrolesvarios",
         * tags={"StaExped Controles Varios"},
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
            $repository = $this->getDoctrine()->getRepository(ControlesVarios::class);
            return $repository->delete($id,$validator,$helper,$em); 
        } catch (HttpException $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

    /**
         * @Route("/api/upload/controlesvarios", methods={"POST"})
         * @OA\Post(
         * summary="Documentos Controles Varios upload",
         * description="Controles Varios upload",
         * operationId="controlesvariosload",
         * tags={"StaExped Controles Varios"},
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
    */ public function uploadControlesVarios(Request $request,ValidatorInterface $validator,FileUploader $fileUploader,ControlesVariosRepository $repository): JsonResponse
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
        $repository = $this->getDoctrine()->getRepository(ControlesVarios::class);
        if ($file) {
            $brochureFileName = $fileUploader->upload($file,"/datastaexped");

            $brochureFileName= $this->getParameter('kernel.project_dir'). '/public'.$brochureFileName;
     
            return $repository->loadControlesVarios($brochureFileName,$validator,$em,$formpermt); 
        }else{
            return new JsonResponse(['error'=>'Error Cargando Archivo'],409);  
        }   


    }



}
