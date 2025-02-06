<?php

namespace App\Controller\StaExped;

use App\Entity\StaExped\CategoriasOtros;
use App\Form\StaExped\CategoriasOtrosType;
use App\Repository\StaExped\CategoriasOtrosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\StaExped\CategoriasOtrosOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

class CategoriasOtrosController extends AbstractController
{

/**
     *  Get list Categorias Otros. 
     * @Route("/api/staexped/categoriasotros/List", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Area",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=CategoriasOtrosOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Categorias Otros")
     * @Security(name="Bearer")
     */
    public function findList(Request $request,CategoriasOtrosRepository $repository): JsonResponse
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
        * @Route("/api/staexped/categoriasotros", methods={"POST"})
        * @OA\Post(
         * summary="Create Categorias Otros",
         * description="Create Categorias Otros",
         * operationId="categoriasotros",
         * tags={"StaExped Categorias Otros"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="tipcategoria", type="string", format="string", example="Civil"), 
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
            $repository = $this->getDoctrine()->getRepository(CategoriasOtros::class);
            return $repository->post($data,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }



}
