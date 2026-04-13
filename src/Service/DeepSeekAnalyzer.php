<?php

namespace App\Service;

class DeepSeekAnalyzer
{
    private const API_URL = 'https://api.deepseek.com/v1/chat/completions';
    
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function analyzeDocument(string $content, string $promptUser): array
    {
        $prompt = $this->buildAnalysisPrompt($content, $promptUser);

        try {
            $data = [
                'model' => 'deepseek-chat',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Eres un analizador de documentos especializado. Proporciona respuestas ÚNICAMENTE en formato JSON válido, sin texto adicional, sin markdown, sin explicaciones.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 2000,
                'temperature' => 0.1
            ];

            $response = $this->makeCurlRequest($data);
            
            if (!isset($response['choices'][0]['message']['content'])) {
                throw new \Exception('Respuesta de API incompleta');
            }

            $rawContent = $response['choices'][0]['message']['content'];
            
            // Limpiar y validar JSON
            $cleanJson = $this->cleanJsonResponse($rawContent);
            $analysis = json_decode($cleanJson, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('JSON inválido desde DeepSeek: ' . json_last_error_msg() . '. Respuesta: ' . substr($rawContent, 0, 200));
            }

            return [
                'summary' => isset($analysis['summary']) ? $analysis['summary'] : 'No se pudo generar resumen',
                'analysis' => isset($analysis['analysis']) ? $analysis['analysis'] : []
            ];

        } catch (\Exception $e) {
            throw new \Exception('Error en análisis DeepSeek: ' . $e->getMessage());
        }
    }

    private function makeCurlRequest(array $data): array
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => self::API_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        
        curl_close($ch);

        if ($response === false) {
            throw new \Exception('Error cURL: ' . $curlError);
        }

        if ($httpCode !== 200) {
            throw new \Exception('Error en API DeepSeek: Código ' . $httpCode . ' - Respuesta: ' . $response);
        }

        $decodedResponse = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Respuesta JSON inválida de la API: ' . json_last_error_msg());
        }

        return $decodedResponse;
    }

    private function buildAnalysisPrompt(string $content, string $promptUser): string
    {
        $truncatedContent = substr($content, 0, 10000);

        if (empty(trim($promptUser))) {
             //$prompt = "Analiza el siguiente documento y proporciona ÚNICAMENTE un JSON válido con:";
             $prompt = <<<PROMPT
                1. "summary": Resumen ejecutivo conciso (máximo 250 palabras)
                2. "analysis": Objeto con:
                   - "main_topics": array de temas principales
                   - "key_points": array de puntos clave  
                   - "recommendations": array de recomendaciones
                   - "keywords": array de palabras clave
                   - "entities": array de entidades identificadas (personas, lugares, fechas)
                   - "sentiment": sentimiento general (positivo, negativo, neutro)
                PROMPT;
        }else{
            $prompt = $promptUser;
             $prompt .= <<<PROMPT
                1. "summary": Resumen ejecutivo conciso
                2. "analysis": Objeto con:
                   - "main_topics": array de temas principales (máximo 5)
                   - "key_points": array de puntos clave (máximo 8)
                   - "keywords": array de palabras clave (máximo 10)
                PROMPT;
        }

        

        /* switch ($tipoAnalisis) {
            case 'analisis_completo':
                $prompt .= <<<PROMPT
                1. "summary": Resumen ejecutivo conciso (máximo 250 palabras)
                2. "analysis": Objeto con:
                   - "main_topics": array de temas principales
                   - "key_points": array de puntos clave  
                   - "recommendations": array de recomendaciones
                   - "keywords": array de palabras clave
                   - "entities": array de entidades identificadas (personas, lugares, fechas)
                   - "sentiment": sentimiento general (positivo, negativo, neutro)
                PROMPT;
                break;

            case 'puntos_clave':
                $prompt .= <<<PROMPT
                1. "summary": Resumen muy breve (máximo 100 palabras)
                2. "analysis": Objeto con:
                   - "key_points": array de puntos clave (máximo 10)
                   - "action_items": array de acciones requeridas
                   - "deadlines": array de fechas importantes
                PROMPT;
                break;

            default: // resumen
                $prompt .= <<<PROMPT
                1. "summary": Resumen ejecutivo conciso (máximo 300 palabras)
                2. "analysis": Objeto con:
                   - "main_topics": array de temas principales (máximo 5)
                   - "key_points": array de puntos clave (máximo 8)
                   - "keywords": array de palabras clave (máximo 10)
                PROMPT;
                break;
        } */

        $prompt .= "\n\nIMPORTANTE: Responde ÚNICAMENTE con el JSON válido, sin texto adicional, sin markdown, sin ```json.\n\n";
        $prompt .= "Contenido del documento:\n{$truncatedContent}";

        return $prompt;
    }

    private function cleanJsonResponse(string $rawResponse): string
    {
        // Eliminar markdown code blocks
        $clean = preg_replace('/```json\s*/', '', $rawResponse);
        $clean = preg_replace('/```\s*/', '', $clean);
        
        // Buscar el primer { y el último }
        $startPos = strpos($clean, '{');
        $endPos = strrpos($clean, '}');
        
        if ($startPos === false || $endPos === false) {
            throw new \Exception('No se encontró JSON válido en la respuesta');
        }
        
        $jsonStr = substr($clean, $startPos, $endPos - $startPos + 1);
        
        // Validar que sea JSON válido
        json_decode($jsonStr);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Intentar corregir JSON incompleto
            return $this->repairJson($jsonStr);
        }
        
        return $jsonStr;
    }

    private function repairJson(string $brokenJson): string
    {
        // Intentar completar el JSON si está truncado
        $repaired = $brokenJson;
        
        // Contar llaves para balancear
        $openBraces = substr_count($repaired, '{');
        $closeBraces = substr_count($repaired, '}');
        $openBrackets = substr_count($repaired, '[');
        $closeBrackets = substr_count($repaired, ']');
        
        // Balancear llaves
        while ($openBraces > $closeBraces) {
            $repaired .= '}';
            $closeBraces++;
        }
        
        // Balancear corchetes
        while ($openBrackets > $closeBrackets) {
            $repaired .= ']';
            $closeBrackets++;
        }
        
        // Validar nuevamente
        json_decode($repaired);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $repaired;
        }
        
        throw new \Exception('No se pudo reparar el JSON: ' . $brokenJson);
    }
}