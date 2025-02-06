<?php

namespace App\Controller\Calendario;

use App\Entity\Calendario\Diasnolaborables;
use App\Form\Calendario\DiasnolaborablesType;
use App\Repository\Calendario\DiasnolaborablesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\Proyecto\DiasnolaborablesOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;


class DiasnolaborablesController extends AbstractController
{

/**
        * @Route("/api/diasnolaborables", methods={"POST"})
        * @OA\Post(
         * summary="Create Dias no Laborables",
         * description="Create Dias no Laborables",
         * operationId="diasnolaborables",
         * tags={"Dias no laborables"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Tipo Categoria",
         *    @OA\JsonContent(
         *       required={"username","password"},
         *       @OA\Property(property="descripcion", type="string", format="string", example="Semana Santa"),
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
            $repository = $this->getDoctrine()->getRepository(Diasnolaborables::class);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

    /**
        *  Get Dias no Laborables  List.
        * @Route("/api/diasnolaborables/List", methods={"GET"})
        * @OA\Post(
         * summary="Dias no Laborables List",
         * description="Dias no Laborables List",
         * operationId="diasnolaborableslist",
         * tags={"Dias no laborables"},
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
         * @OA\Tag(name="Dias no laborables")
         * @Security(name="Bearer")
    */  

    public function findList(Request $request,DiasnolaborablesRepository $diasnolaborablesRepository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $diasnolaborablesRepository
        ->findList();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

    /**
        * @Route("/api/diasnolaborables/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Dias no Laborables",
         * description="Update Dias no Laborables",
         * operationId="updatediasnolaborables",
         * tags={"Dias no laborables"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Dias no Laborables",
         *    @OA\JsonContent(
         *       required={"nombre"},
         *       @OA\Property(property="descripcion", type="string", format="string", example="Semana Santa"),
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
            $repository = $this->getDoctrine()->getRepository(Diasnolaborables::class);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

}