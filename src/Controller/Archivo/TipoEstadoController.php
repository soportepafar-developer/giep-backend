<?php

namespace App\Controller\Archivo;

use App\Entity\Archivo\TipoEstado;
use App\Repository\Archivo\TipoEstadoRepository;
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

class TipoEstadoController extends AbstractController
{
  
       /**
        *  Get Tipo Estado List.
        * @Route("/api/tipoestado/List", methods={"GET"})
        * @OA\Post(
         * summary="Tipo Estado List",
         * description="Tipo Estado List",
         * operationId="tipoestadolist",
         * tags={"Tipo Estado"},
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
         * @OA\Tag(name="Tipo Estado")
         * @Security(name="Bearer")
    */  

    public function findList(Request $request,TipoEstadoRepository $tipoestadoRepository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $tipoestadoRepository
        ->findList();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

  




}
