<?php

namespace App\Controller\DocumentoDigital;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

use App\Repository\DocumentoDigital\DireccionAlmacenRepository;
use App\Dto\DocumentoDigital\DireccionAlmacenOutPutDto;

class DireccionAlmacenController extends AbstractController
{

  /**
     *  Get list archivodigitalstatus. 
     * @Route("/api/archivodigitaldireccionalmacen/list", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns Status",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=DireccionAlmacenOutPutDto::class))
     *     )
     * )
     * @OA\Tag(name="Archivos Digital")
     * @Security(name="Bearer")
     */
    public function findList(Request $request,DireccionAlmacenRepository $repository): JsonResponse
    {
        $em = $this->getDoctrine()->getManager('documentodigital');
        $data = $repository
        ->findList($em);
        if (!$data) {
            return new JsonResponse(['msg'=>'No existen Registros'],200);  
        }   
         return new JsonResponse($data,200);  
    }

}
