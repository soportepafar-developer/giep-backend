<?php

namespace App\Controller\Proyecto;

use App\Entity\Proyecto\Roles;
use App\Form\Proyecto\RolesType;
use App\Repository\Proyecto\RolesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Dto\Proyecto\RolesOutPutDto;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

class RolesController extends AbstractController
{
   /**
        *  Get Pais by Pais Id.
        * @Route("/api/roles/List", methods={"GET"})
        * @OA\Post(
         * summary="Empresa Roles List",
         * description="Empresa Roles List",
         * operationId="Empresaroleslist",
         * tags={"Empresa Roles"},
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
         * @OA\Tag(name="Empresa Roles")
         * @Security(name="Bearer")
    */  

    public function findList(Request $request,RolesRepository $rolesRepository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);
        $data = $rolesRepository
        ->findList();
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }


        /**
        * @Route("/api/roles", methods={"POST"})
        * @OA\Post(
         * summary="Create Empresa Roles",
         * description="Create Empresa Roles",
         * operationId="Empresaroles",
         * tags={"Empresa Roles"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="roles", type="string", format="string", example="ProductOwner 1"),
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
            $repository = $this->getDoctrine()->getRepository(Roles::class);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

        /**
        * @Route("/api/roles/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Empresa Roles",
         * description="Update Empresa Roles",
         * operationId="updateEmpresaRoles",
         * tags={"Empresa Roles"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Empresa Roles",
         *    @OA\JsonContent(
         *       required={"nombre","descripcion"},
         *       @OA\Property(property="roles", type="string", format="string", example="ProductOwner 1 Modificado"),
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
            $repository = $this->getDoctrine()->getRepository(Roles::class);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }


}
