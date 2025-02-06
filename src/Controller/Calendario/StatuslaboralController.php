<?php

namespace App\Controller\Calendario;

use App\Entity\Calendario\Statuslaboral;
use App\Form\Calendario\StatuslaboralType;
use App\Repository\Calendario\StatuslaboralRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\Proyecto\StatuslaboralOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

class StatuslaboralController extends AbstractController
{
   /**
        * @Route("/api/statuslaboral", methods={"POST"})
        * @OA\Post(
         * summary="Create Status Laboral",
         * description="Create Status Laboral",
         * operationId="statuslaboral",
         * tags={"Status Laboral"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Tipo Categoria",
         *    @OA\JsonContent(
         *       required={"username","password"},
         *       @OA\Property(property="descripcion", type="string", format="string", example="Laborable"),
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
            $repository = $this->getDoctrine()->getRepository(Statuslaboral::class);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

        /**
        *  Get Status Laboral List.
        * @Route("/api/statuslaboral/List", methods={"GET"})
        * @OA\Post(
         * summary="Status Laboral List",
         * description="Status Laboral List",
         * operationId="statuslaboral",
         * tags={"Status Laboral"},
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
         * @OA\Tag(name="Status Laboral")
         * @Security(name="Bearer")
    */  

    public function findList(Request $request,StatuslaboralRepository $statuslaboralRepository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $statuslaboralRepository
        ->findList();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

    /**
        * @Route("/api/statuslaboral/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Status Laboral",
         * description="Update Status Laboral",
         * operationId="updateStatusLaboral",
         * tags={"Status Laboral"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Calendario",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="descripcion", type="string", format="string", example="Laborable"),
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
            $repository = $this->getDoctrine()->getRepository(Statuslaboral::class);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


}
