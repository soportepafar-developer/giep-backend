<?php

namespace App\Controller\Encuesta;

use App\Entity\Encuesta\TipoCategoria;
use App\Form\Encuesta\TipoCategoriaType;
use App\Repository\Encuesta\TipoCategoriaRepository;

use App\Dto\Encuesta\TipoCategoriaOutPutDto;

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

class TipoCategoriaController extends AbstractController
{

    /**
     *  Get list categoria. 
     * @Route("/api/encuesta/tipocategoria/list", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Categoria",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TipoCategoriaOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Tipo Categoria")
     * @Security(name="Bearer")
     */
    public function findListNoPagined(Request $request,TipoCategoriaRepository $tipoCategoriaRepository): JsonResponse
    {
        $data = $tipoCategoriaRepository
        ->findList();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }



    /**
        * @Route("/api/encuesta/tipocategoria/pagined", methods={"POST"})
        * @OA\Post(
         * summary="Tipo Categorias pagined",
         * description="Tipo Categorias pagined",
         * operationId="tipocategoriaslist",
         * tags={"Tipo Categoria"},
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
    */   
    public function findList(Request $request,TipoCategoriaRepository $tipoCategoriaRepository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);

        $data = $tipoCategoriaRepository
        ->findAllPage($param);
       /*  $data = $tipoCategoriaRepository
        ->findList(); */
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

    /**
        * @Route("/api/encuesta/tipocategoria", methods={"POST"})
        * @OA\Post(
         * summary="Create Tipo Categoria",
         * description="Create Tipo Categoria",
         * operationId="tipocategoria",
         * tags={"Tipo Categoria"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Tipo Categoria",
         *    @OA\JsonContent(
         *       required={"username","password"},
         *       @OA\Property(property="nombre", type="string", format="string", example="METAS/NECESIDAD DE LOGRO2222"),
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
            $repository = $this->getDoctrine()->getRepository(TipoCategoria::class);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

    /**
     *  Get TipoCategoria by TipoCategoria Id. 
     * tags={"Tipo Categoria"},
     * * description="Tipo Categorias Byid",
     * @Route("/api/encuesta/tipocategoria/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TipoCategoriaOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Tipo Categoria")
     * @Security(name="Bearer")
     */
    public function findById($id,TipoCategoriaRepository $repository): JsonResponse
    {
        $data = $repository
        ->findTipoByCategoria($id);
         return $data;  
    }
 
   /**
        * @Route("/api/encuesta/tipocategoria/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Tipo Categoria",
         * description="Update Tipo Categoria",
         * operationId="updateTipoCategoria",
         * tags={"Tipo Categoria"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Tipo Categoria",
         *    @OA\JsonContent(
         *       required={"nombre","descripcion"},
         *       @OA\Property(property="nombre", type="string", format="string", example="METAS/NECESIDAD DE LOGRO"),
         *       @OA\Property(property="statusId", type="integer", format="string", example="1"),
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
            $repository = $this->getDoctrine()->getRepository(TipoCategoria::class);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }



    /**
        * @Route("/api/encuesta/tipocategoria/{id}", methods={"DELETE"})
        * @OA\Delete(
         * summary="Delete TipoCategoria",
         * description="Delete TipoCaTegoria",
         * operationId="deletecategoria",
         * tags={"Tipo Categoria"},
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
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(TipoCategoria::class);
            return $repository->delete($id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

}
