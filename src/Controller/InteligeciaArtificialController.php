<?php

namespace App\Controller;

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

class InteligeciaArtificialController extends AbstractController
{
     /**
        * @Route("/api/inteligecia/artificial", methods={"POST"})
        * @OA\Post(
         * summary="Inteligencia Artificial IA",
         * description="Inteligencia Artificial IA",
         * operationId="InteligenciaArtificialIA",
         * tags={"Inteligecia Artificial"},
         * @OA\RequestBody(
         *    required=true,
         *    description="parametro",
         *    @OA\JsonContent(
         *       required={"page"},
         *       @OA\Property(property="content", type="string", format="string", example="El titulo del archivo"),
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
    public function index(Request $request): JsonResponse
    {
      $param = json_decode($request->getContent(),true);
      $envIa = $_ENV['APP_ENV_IA'] ?? 'valor_por_defecto';
      if ($envIa == "Gropq") {
            $model = $param['model'] ?? 'default prompt';
            $content = $param['content'] ?? 'default prompt';
            $curl = curl_init();
            $payload = json_encode([
                "model" => $model,
                "messages" => [
                    ["role" => "user", "content" => $content]
                ],
                "stream" => false
            ]);

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.groq.com/openai/v1/chat/completions",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/json",
                    "Authorization: Bearer " . $_ENV['GROPQ_API_KEY'] // o getenv('GROPQ_API_KEY')
                ],
            ]);
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return new JsonResponse(['msg' => "Error en la llamada a Gropq: " . $err], 500);
            } else {
                $data = json_decode($response, true);
                return new JsonResponse($data);
            }
      }elseif ($envIa=="DeepSeek") {
            $param = json_decode($request->getContent(),true);
            $envIa = $_ENV['APP_ENV_IA'] ?? 'valor_por_defecto';
                    $model = $param['model'] ?? 'default prompt';
                    $content = $param['content'] ?? 'default prompt';
                    $curl = curl_init();
                    $payload = json_encode([
                        "model" => $model,
                        "messages" => [
                            ["role" => "user", "content" => $content]
                        ],
                        "stream" => false
                    ]);

                    curl_setopt_array($curl, [
                        CURLOPT_URL => "https://api.deepseek.com/chat/completions",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $payload,
                        CURLOPT_HTTPHEADER => [
                            "Content-Type: application/json",
                            "Authorization: Bearer " . $_ENV['DEEPSEEK_API_KEY'] // o getenv('DEEPSEEK_API_KEY')
                        ],
                    ]);
                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    curl_close($curl);
                    if ($err) {
                        return new JsonResponse(['msg' => "Error en la llamada a DeepSeek: " . $err], 500);
                    } else {
                        $data = json_decode($response, true);
                        return new JsonResponse($data);
                    }
          

      }elseif ($envIa=="Gemini") {

            $param = json_decode($request->getContent(), true);
            $envIa = $_ENV['APP_ENV_IA'] ?? 'valor_por_defecto';
                $content = $param['content'] ?? 'Explain how AI works in a few words';
                $model = $param['model'] ?? 'default prompt';
                $payload = json_encode([
                    "contents" => [
                        [
                            "parts" => [
                                ["text" => $content]
                            ]
                        ]
                    ]
                ]);
                $UrlModelo = "https://generativelanguage.googleapis.com/v1beta/models/" . $model;
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => $UrlModelo,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $payload,
                    CURLOPT_HTTPHEADER => [
                        "Content-Type: application/json",
                        "x-goog-api-key: " . $_ENV['GEMINI_API_KEY'] // o getenv('GEMINI_API_KEY')
                    ],
                ]);

                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                if ($err) {
                    return new JsonResponse(['msg' => "Error en la llamada a Gemini: " . $err], 500);
                } else {
                    $data = json_decode($response, true);
                    return new JsonResponse($data);
                }
        }  

      return new JsonResponse([
        'env_ia' => $envIa
      ]);

    }
}
