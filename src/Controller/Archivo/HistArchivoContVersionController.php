<?php

namespace App\Controller\Archivo;

use App\Entity\Archivo\HistArchivoContVersion;
use App\Repository\Archivo\HistArchivoContVersionRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\FileUploader;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\MimeType\FileinfoMimeTypeGuesser;
use Symfony\Component\Mime\MimeTypes;


class HistArchivoContVersionController extends AbstractController
{

    private $params;

    public function __construct(ParameterBagInterface $params)

    {
        $this->params = $params;

    }

     /**
        * Get an Archivo by Id.
        * @Route("/api/archivo/downloadhistorico/file/{id}", methods={"GET"})
        * @OA\Post(
         * summary="download File History",
         * description="download File History",
         * operationId="downloadfilehistory",
         * tags={"Historico Archivo"},
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
         * @OA\Tag(name="Historico Archivo")
         * @Security(name="Bearer")
    */   

    public function downloadFiles($id,Request $request,ValidatorInterface $validator,FileUploader $fileUploader,HistArchivoContVersionRepository $historicoarchivosRepository): Response
    {
        $idfile = $id;
        $dircctoy = $this->params->get('photos_directory');
        $data = $historicoarchivosRepository->getBuscarHistoricoArchivosById($idfile,$dircctoy);
        if ($data) {
            return $data;  
        }else{
            return new JsonResponse(['msg'=>'No existen Registros'],409);  
        }   
    }



}
