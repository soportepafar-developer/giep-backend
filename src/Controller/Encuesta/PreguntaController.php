<?php

namespace App\Controller\Encuesta;
use App\Dto\Encuesta\PreguntaOutPutDto;
use App\Entity\Encuesta\Pregunta;
use App\Form\Encuesta\PreguntaType;
use App\Repository\Encuesta\PreguntaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;
use Nelmio\ApiDocBundle\Annotation\Security;




class PreguntaController extends AbstractController
{

        /**
        * @Route("/api/encuesta/pregunta/pagined", methods={"POST"})
        * @OA\Post(
         * summary="Preguntas Pagined",
         * description="Preguntas Pagined",
         * operationId="preguntalist",
         * tags={"Preguntas"},
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
    public function findList(Request $request,PreguntaRepository $preguntaRepository): JsonResponse
    {
        $param = json_decode($request->getContent(),true);

        $data = $preguntaRepository
        ->findAllPage($param);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],404);  
        }   
         return new JsonResponse($data,200);  
    }


      /**
        * @Route("/api/encuesta/pregunta", methods={"POST"})
        * @OA\Post(
         * summary="Create Pregunta",
         * description="Create Pregunta",
         * operationId="createPregunta",
         * tags={"Preguntas"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Pregunta",
         *    @OA\JsonContent(
         *       required={"nombre","descripcion"},
         *       @OA\Property(property="pregunta", type="string", example="Posee licencia de conducir"),
         *       @OA\Property(property="orden", type="string", example="1"),
         *       @OA\Property(property="idInput", type="integer", example="1"),
         *       @OA\Property(property="class", type="string", example="btn btn-button"),
         *       @OA\Property(property="obligatorio", type="integer", example="1"),
         *       @OA\Property(property="puntos", type="integer", example="12"),
         *       @OA\Property(property="IdCategoria", type="integer", example="1"),
         *       @OA\Property(property="idInstrumento", type="integer", example="1"),
         *       @OA\Property(property="opciones", type="array", @OA\Items(type="array",@OA\Items()), example={{"opcion":"1"},{"opcion":"2"}}),
         *       @OA\Property(property="seccion", type="integer", example="1")
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
    public function post(Request $request,ValidatorInterface $validator,Helper $helper): JsonResponse
    {   
        try {
            $data = json_decode($request->getContent(),true);
            $repository = $this->getDoctrine()->getRepository(Pregunta::class);
            return $repository->post($data,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

        /**
        * @Route("/api/encuesta/pregunta/actualizar/{id}", methods={"PUT"})
        * @OA\Put(
         * summary="Put Preguntas",
         * description="Update Preguntas",
         * operationId="updatePreguntas",
         * tags={"Preguntas"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Data Preguntas",
         *    @OA\JsonContent(
         *       required={"nombre","descripcion"},
         *       @OA\Property(property="pregunta", type="string", example="Posee licencia de conducir"),
         *       @OA\Property(property="orden", type="string", example="1"),
         *       @OA\Property(property="idInput", type="integer", example="1"),
         *       @OA\Property(property="class", type="string", example="btn btn-button"),
         *       @OA\Property(property="obligatorio", type="integer", example="1"),
         *       @OA\Property(property="puntos", type="integer", example="12"),
         *       @OA\Property(property="IdCategoria", type="integer", example="1"),
         *       @OA\Property(property="idInstrumento", type="integer", example="1"),
         *       @OA\Property(property="opciones", type="array", @OA\Items(type="array",@OA\Items()), example={{"opcion":"1"},{"opcion":"2"}}),
         *       @OA\Property(property="seccion", type="integer", example="1")

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
            $repository = $this->getDoctrine()->getRepository(Pregunta::class);
            return $repository->put($data,$id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

    /**
     *  Get an Pregunta. 
     * @Route("/api/encuesta/pregunta/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Pregunta",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=App\Dto\Encuesta\PreguntaOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Preguntas")
     * @Security(name="Bearer")
     */
    public function findById($id,PreguntaRepository $repository): JsonResponse
    {
        $data = $repository
        ->findById($id);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],404);  
        }   
         return new JsonResponse($data,200);  
    }


    /**
        * @Route("/api/encuesta/pregunta/{id}", methods={"DELETE"})
        * @OA\Delete(
         * summary="Delete Preguntas",
         * description="Delete Preguntas",
         * operationId="deletepregunta",
         * tags={"Preguntas"},
         * @OA\Response(
         *    response=422,
         *    description="Wrong credentials response",
         *    @OA\JsonContent(
         *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
         *        )
         *     )
         * )
    */
    public function delete($id,ValidatorInterface $validator,Helper $helper): JsonResponse
    {
        try {
            $em =$this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(Pregunta::class);
            return $repository->delete($id,$validator,$helper); 
        } catch (Exception $e) {
            return new JsonResponse(['msg'=>'Error del Servidor'],500);
        }
    }

}
