<?php

namespace App\Controller\StaExped;

use App\Entity\StaExped\TiposRespuestas;
use App\Dto\StaExped\TiposRespuestasOutPutDto;
use App\Repository\StaExped\TiposRespuestasRepository;
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



class TiposRespuestasController extends AbstractController
{
/**
     *  Get list Tipos Respuestas. 
     * @Route("/api/staexped/tiposrespuestas/List", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Respuestas",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=TiposRespuestasOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Tipos Respuestas")
     * @Security(name="Bearer")
     */
    public function findList(Request $request,TiposRespuestasRepository $repository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('customer');
        $data = $repository
        ->findList($em);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

    /**
        * @Route("/api/staexped/tiposrespuestas", methods={"POST"})
        * @OA\Post(
         * summary="Create Tipos Respuestas",
         * description="Create Tipos Respuestas",
         * operationId="tiposrespuestas",
         * tags={"StaExped Tipos Respuestas"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="respuestas", type="string", format="string", example="No se"), 
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
            $repository = $this->getDoctrine()->getRepository(TiposRespuestas::class);
            return $repository->post($data,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


}
