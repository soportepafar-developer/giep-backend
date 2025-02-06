<?php

namespace App\Controller\Proyecto;

use App\Entity\Proyecto\Traza;
use App\Form\Proyecto\TrazaType;
use App\Repository\Proyecto\TrazaRepository;
use App\Dto\Proyecto\EmpresaOutPutDto;
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




class TrazaController extends AbstractController
{

       /**
        * @Route("/api/proyecto/traza", methods={"POST"})
        * @OA\Post(
         * summary="Create Traza",
         * description="Create Traza",
         * operationId="CreateTraza",
         * tags={"Traza"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"tipoEntidad"},
         *       @OA\Property(property="tipoEntidad", type="string", format="string", example="Eliminar PMO"),
         *       @OA\Property(property="idEntidad", type="string", format="string", example="Proyecto"),
         *       @OA\Property(property="createdBy", type="integer", format="string", example="1"),
         *       @OA\Property(property="sqlInstruction", type="string", format="string", example="Inser into"),
         *       @OA\Property(property="accion", type="string", format="integer", example="1"),
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
    public function post(Request $request,ValidatorInterface $validator,Helper $helper,TrazaRepository $repository): Response
    {   
        try {
            $data = json_decode($request->getContent(),true);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


            /**
        * @Route("/api/proyecto/traza/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Traza",
         * description="Update Traza",
         * operationId="updateTraza",
         * tags={"Traza"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Traza",
         *    @OA\JsonContent(
         *       required={"tipoEntidad","tipoEntidad"},
         *       @OA\Property(property="tipoEntidad", type="string", format="string", example="Eliminar PMO"),
         *       @OA\Property(property="idEntidad", type="string", format="string", example="Proyecto"),
         *       @OA\Property(property="createdBy", type="integer", format="string", example="1"),
         *       @OA\Property(property="sqlInstruction", type="string", format="string", example="Inser into"),
         *       @OA\Property(property="accion", type="string", format="integer", example="1"),         *    ),
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
            $repository = $this->getDoctrine()->getRepository(Traza::class);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }




}
