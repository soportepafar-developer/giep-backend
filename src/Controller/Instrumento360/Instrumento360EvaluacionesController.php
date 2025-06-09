<?php

namespace App\Controller\Instrumento360;

use App\Repository\Instrumento360\Instrumento360EvaluacionesRepository;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\Helper;
use Symfony\Component\Validator\Constraints\Json;

/**
 * @OA\Tag(name="Instrumento360 Evaluaciones")
 */
class Instrumento360EvaluacionesController extends AbstractController
{
    private $evaluacionesRepository;

    public function __construct(Instrumento360EvaluacionesRepository $evaluacionesRepository)
    {
        $this->evaluacionesRepository = $evaluacionesRepository;
    }


} 