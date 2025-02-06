<?php

namespace App\Controller\StaExped;

use App\Entity\StaExped\Profesion;
use App\Form\StaExped\ProfesionType;
use App\Repository\StaExped\ProfesionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\StaExped\ProfesionOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

class ProfesionController extends AbstractController
{

/**
     *  Get list Profesion. 
     * @Route("/api/staexped/profesion/List", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Profesion",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=ProfesionOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="StaExped Profesion")
     * @Security(name="Bearer")
     */
    public function findList(Request $request,ProfesionRepository $repository): JsonResponse
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
        * @Route("/api/staexped/profesion", methods={"POST"})
        * @OA\Post(
         * summary="Create Profesion",
         * description="Create Profesion",
         * operationId="profesion",
         * tags={"StaExped Profesion"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="descprofesion", type="string", format="string", example="Ingeniero Informatica"), 
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
            $repository = $this->getDoctrine()->getRepository(Profesion::class);
            return $repository->post($data,$validator,$helper,$em); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }




}
