<?php

namespace App\Controller\StaExped;

use App\Entity\StaExped\Area;
use App\Form\StaExped\AreaType;
use App\Repository\StaExped\AreaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Dto\StaExped\AreaOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

class AreaController extends AbstractController
{

/**
     *  Get list Area. 
     * @Route("/api/staexped/area/List", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Area",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=AreaOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Area")
     * @Security(name="Bearer")
     */
    public function findList(Request $request,AreaRepository $repository): JsonResponse
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
     *  Get an Datos Area by Ci. 
     * @Route("/api/staexped/area/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Area",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=AreaOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Area")
     * @Security(name="Bearer")
     */
    public function getAreaById($id,AreaRepository $datosareaRepository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('customer');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $data = $datosareaRepository
        ->getAreaById($id,$em);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

    /**
        * @Route("/api/staexped/area", methods={"POST"})
        * @OA\Post(
         * summary="Create Area",
         * description="Create Area",
         * operationId="area",
         * tags={"StaExped Area"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="iddepartamentos", type="integer", format="integer", example="1"), 
         *       @OA\Property(property="descarea", type="string", format="string", example="Soporte"), 
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
            $repository = $this->getDoctrine()->getRepository(Area::class);
            return $repository->post($data,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }



}
