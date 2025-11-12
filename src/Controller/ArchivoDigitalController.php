<?php

namespace App\Controller;

use App\Entity\Document;
use App\Service\DocumentProcessor;
use App\Service\DeepSeekAnalyzer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;


class ArchivoDigitalController extends AbstractController
{
  /**
     * @Route("/api/archivodigital/upload/archivo_deepseek", methods={"POST"})
     * @OA\Post(
     *     summary="upload Archivo DeepSeek",
     *     description="Sube y analiza un archivo usando DeepSeek AI",
     *     operationId="Archivouploaddeepseek",
     *     tags={"Archivos Digital DeepSeek"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="Archivo a analizar",
     *                     property="archivo",
     *                     type="string",
     *                     format="binary"
     *                 ), 
     *                 @OA\Property(
     *                     description="Prompt usuario (opcional)",
     *                     property="promptuser",
     *                     type="string",
     *                     enum={"resumen", "Realizame un análisis muy general", "puntos_clave"},
     *                     default="resumen"
     *                 )
     * 
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Archivo analizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Archivo analizado exitosamente"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nombre_original", type="string", example="documento.pdf"),
     *                 @OA\Property(property="resumen", type="string", example="Resumen generado por DeepSeek..."),
     *                 @OA\Property(property="analisis", type="object",
     *                     @OA\Property(property="temas_principales", type="array", @OA\Items(type="string")),
     *                     @OA\Property(property="puntos_clave", type="array", @OA\Items(type="string")),
     *                     @OA\Property(property="keywords", type="array", @OA\Items(type="string")),
     *                     @OA\Property(property="recomendaciones", type="array", @OA\Items(type="string"))
     *                 ),
     *                 @OA\Property(property="fecha_analisis", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error en la solicitud",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No se encontró archivo en la solicitud")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Archivo Incorrecto",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Tipo de archivo no soportado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error al procesar el archivo")
     *         )
     *     )
     * )
     */
    public function uploadArchivodeepseek(
        Request $request,
        ValidatorInterface $validator,
        DocumentProcessor $documentProcessor,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        try {
            // Verificar si se envió un archivo
            if (!$request->files->has('archivo')) {
                return $this->json([
                    'success' => false,
                    'message' => 'No se encontró archivo en la solicitud'
                ], 400);
            }

            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $archivo */
            $archivo = $request->files->get('archivo');
            $promptUser = $request->request->get('promptuser');

            // Validar tipo de archivo
            $mimeTypesPermitidos = [
                'application/pdf',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'text/plain',
                'application/msword',
                'text/csv',
                'application/vnd.ms-excel'
            ];

            if (!in_array($archivo->getClientMimeType(), $mimeTypesPermitidos)) {
                return $this->json([
                    'success' => false,
                    'message' => sprintf(
                        'Tipo de archivo no soportado: %s. Tipos permitidos: PDF, Word, Excel, TXT, CSV',
                        $archivo->getClientMimeType()
                    )
                ], 422);
            }

            // Validar tamaño del archivo (10MB máximo)
            if ($archivo->getSize() > 50 * 1024 * 1024) {
                return $this->json([
                    'success' => false,
                    'message' => 'El archivo excede el tamaño máximo permitido (50MB)'
                ], 422);
            }

            // Procesar el archivo
            $document = $documentProcessor->processUploadedFile($archivo,$promptUser);
            
            // Guardar en base de datos
            $entityManager->persist($document);
            $entityManager->flush();

            // Preparar respuesta
            $responseData = [
                'success' => true,
                'message' => 'Archivo analizado exitosamente',
                'data' => [
                    'id' => $document->getId(),
                    'nombre_original' => $document->getOriginalName(),
                    'tipo_archivo' => $document->getFileType(),
                    'tamano' => $document->getFileSize(),
                    'resumen' => $document->getSummary(),
                    'analisis' => $document->getAnalysis(),
                    'fecha_analisis' => $document->getUploadedAt()->format('Y-m-d H:i:s'),
                    'metadatos' => [
                        'temas_principales_count' => count($document->getAnalysis()['main_topics'] ?? []),
                        'puntos_clave_count' => count($document->getAnalysis()['key_points'] ?? []),
                        'keywords_count' => count($document->getAnalysis()['keywords'] ?? [])
                    ]
                ]
            ];

            return $this->json($responseData, 200);

        } catch (\Exception $e) {
            // Log del error
            // $this->logger->error('Error en uploadArchivodeepseek: ' . $e->getMessage());

            return $this->json([
                'success' => false,
                'message' => 'Error interno al procesar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @Route("/api/archivodigital/{id}/analisis", methods={"GET"})
     * @OA\Get(
     *     summary="Obtener análisis de archivo",
     *     description="Obtiene el análisis DeepSeek de un archivo previamente procesado",
     *     operationId="ObtenerAnalisisArchivo",
     *     tags={"Archivos Digital DeepSeek"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del archivo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Análisis obtenido exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Archivo no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Archivo no encontrado")
     *         )
     *     )
     * )
     */
    public function getAnalisisArchivo(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $document = $entityManager->getRepository(Document::class)->find($id);

        if (!$document) {
            return $this->json([
                'success' => false,
                'message' => 'Archivo no encontrado'
            ], 404);
        }

        return $this->json([
            'success' => true,
            'data' => [
                'id' => $document->getId(),
                'nombre_original' => $document->getOriginalName(),
                'resumen' => $document->getSummary(),
                'analisis' => $document->getAnalysis(),
                'fecha_analisis' => $document->getUploadedAt()->format('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * @Route("/api/archivodigital/{id}", methods={"DELETE"})
     * @OA\Delete(
     *     summary="Eliminar archivo analizado",
     *     description="Elimina un archivo y su análisis de la base de datos",
     *     operationId="EliminarArchivo",
     *     tags={"Archivos Digital DeepSeek"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del archivo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Archivo eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Archivo eliminado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Archivo no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Archivo no encontrado")
     *         )
     *     )
     * )
     */
    public function deleteArchivo(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $document = $entityManager->getRepository(Document::class)->find($id);

        if (!$document) {
            return $this->json([
                'success' => false,
                'message' => 'Archivo no encontrado'
            ], 404);
        }

        // Eliminar archivo físico si existe
        $uploadsDirectory = $this->getParameter('app.uploads_directory');
        $filePath = $uploadsDirectory . '/' . $document->getFilename();
        
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $entityManager->remove($document);
        $entityManager->flush();

        return $this->json([
            'success' => true,
            'message' => 'Archivo eliminado exitosamente'
        ]);
    }
}