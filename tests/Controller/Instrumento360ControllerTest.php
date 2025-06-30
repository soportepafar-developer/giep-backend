<?php

namespace App\Tests\Controller;

use App\Entity\Instrumento360\Instrumento360;
use App\Entity\Instrumento360\SeccionEvaluacion360;
use App\Entity\Instrumento360\PreguntaEvaluacion360;
use App\Entity\Instrumento360\OpcionesEvaluacion360;
use App\Entity\Instrumento360\RespuestaEvaluacion360;
use App\Entity\User;
use App\Entity\Proyecto\Empresa;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManagerInterface;

class Instrumento360ControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;
    private $testInstrumento;
    private $testSeccion;
    private $testPregunta;
    private $testOpcion;
    private $testRespuesta;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        
        // Crear datos de prueba
        $this->createTestData();
    }

    protected function tearDown(): void
    {
        // Limpiar datos de prueba
        $this->cleanupTestData();
        
        parent::tearDown();
    }

    private function createTestData(): void
    {
        // Crear empresa de prueba
        $empresa = new Empresa();
        $empresa->setNombre('Empresa Test');
        $this->entityManager->persist($empresa);

        // Crear usuario de prueba
        $user = new User();
        $user->setUserName('testuser');
        $user->setEmail('test@example.com');
        $user->setIdempresa($empresa);
        $this->entityManager->persist($user);

        // Crear instrumento de prueba
        $this->testInstrumento = new Instrumento360();
        $this->testInstrumento->setNombre('Instrumento Test');
        $this->testInstrumento->setDescripcion('Descripción de prueba');
        $this->testInstrumento->setPublicar(0); // No publicado
        $this->testInstrumento->setEmpresa($empresa);
        $this->testInstrumento->setCreateBy($user->getUserName());
        $this->entityManager->persist($this->testInstrumento);

        // Crear sección de prueba
        $this->testSeccion = new SeccionEvaluacion360();
        $this->testSeccion->setNombre('Sección Test');
        $this->testSeccion->setOrden(1);
        $this->testSeccion->setInstrumento($this->testInstrumento);
        $this->testSeccion->setUpdateBy($user->getUserName());
        $this->testSeccion->setIdempresa($empresa);
        $this->entityManager->persist($this->testSeccion);

        // Crear pregunta de prueba
        $this->testPregunta = new PreguntaEvaluacion360();
        $this->testPregunta->setPregunta('Pregunta Test');
        $this->testPregunta->setOrden(1);
        $this->testPregunta->setObligatorio(1);
        $this->testPregunta->setPuntos(10);
        $this->testPregunta->setIdInstrumento($this->testInstrumento);
        $this->testPregunta->setSeccion($this->testSeccion);
        $this->testPregunta->setIdempresa($empresa);
        $this->entityManager->persist($this->testPregunta);

        // Crear opción de prueba
        $this->testOpcion = new OpcionesEvaluacion360();
        $this->testOpcion->setNombre('Opción Test');
        $this->testOpcion->setValor('test_value');
        $this->testOpcion->setPuntos(5);
        $this->testOpcion->setCorrecta(1);
        $this->testOpcion->setIdPregunta($this->testPregunta);
        $this->testOpcion->setUpdateBy($user->getUserName());
        $this->testOpcion->setIdempresa($empresa);
        $this->entityManager->persist($this->testOpcion);

        // Crear respuesta de prueba
        $this->testRespuesta = new RespuestaEvaluacion360();
        $this->testRespuesta->setIdUser($user);
        $this->testRespuesta->setIdPregunta($this->testPregunta);
        $this->testRespuesta->setIdOpcion($this->testOpcion);
        $this->testRespuesta->setEntradaTexto('Respuesta de prueba');
        $this->testRespuesta->setCreateBy($user->getUserName());
        $this->testRespuesta->setIdempresa($empresa);
        $this->entityManager->persist($this->testRespuesta);

        $this->entityManager->flush();
    }

    private function cleanupTestData(): void
    {
        // Limpiar en orden inverso para evitar violaciones de integridad referencial
        if ($this->testRespuesta) {
            $this->entityManager->remove($this->testRespuesta);
        }
        if ($this->testOpcion) {
            $this->entityManager->remove($this->testOpcion);
        }
        if ($this->testPregunta) {
            $this->entityManager->remove($this->testPregunta);
        }
        if ($this->testSeccion) {
            $this->entityManager->remove($this->testSeccion);
        }
        if ($this->testInstrumento) {
            $this->entityManager->remove($this->testInstrumento);
        }
        
        $this->entityManager->flush();
    }

    public function testDeleteSeccion(): void
    {
        // Simular autenticación (ajustar según tu sistema de autenticación)
        $this->client->request('DELETE', '/api/instrumento360/seccion/' . $this->testSeccion->getId());
        
        $this->assertResponseIsSuccessful();
        
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('msg', $response);
        $this->assertStringContainsString('eliminada correctamente', $response['msg']);
        
        // Verificar que la sección fue eliminada
        $seccion = $this->entityManager->getRepository(SeccionEvaluacion360::class)->find($this->testSeccion->getId());
        $this->assertNull($seccion);
        
        // Verificar que las preguntas fueron eliminadas
        $pregunta = $this->entityManager->getRepository(PreguntaEvaluacion360::class)->find($this->testPregunta->getId());
        $this->assertNull($pregunta);
        
        // Verificar que las opciones fueron eliminadas
        $opcion = $this->entityManager->getRepository(OpcionesEvaluacion360::class)->find($this->testOpcion->getId());
        $this->assertNull($opcion);
        
        // Verificar que las respuestas fueron eliminadas
        $respuesta = $this->entityManager->getRepository(RespuestaEvaluacion360::class)->find($this->testRespuesta->getId());
        $this->assertNull($respuesta);
    }

    public function testDeleteSeccionNotFound(): void
    {
        $this->client->request('DELETE', '/api/instrumento360/seccion/99999');
        
        $this->assertResponseStatusCodeSame(404);
        
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('msg', $response);
        $this->assertStringContainsString('No existe la sección', $response['msg']);
    }

    public function testDeleteSeccionInstrumentoPublicado(): void
    {
        // Publicar el instrumento
        $this->testInstrumento->setPublicar(1);
        $this->entityManager->flush();
        
        $this->client->request('DELETE', '/api/instrumento360/seccion/' . $this->testSeccion->getId());
        
        $this->assertResponseStatusCodeSame(400);
        
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('msg', $response);
        $this->assertStringContainsString('instrumento publicado', $response['msg']);
    }

    public function testDeleteSecciones(): void
    {
        // Crear una segunda sección para probar eliminación múltiple
        $seccion2 = new SeccionEvaluacion360();
        $seccion2->setNombre('Sección Test 2');
        $seccion2->setOrden(2);
        $seccion2->setInstrumento($this->testInstrumento);
        $seccion2->setUpdateBy('testuser');
        $seccion2->setIdempresa($this->testInstrumento->getEmpresa());
        $this->entityManager->persist($seccion2);
        $this->entityManager->flush();
        
        $seccionIds = [$this->testSeccion->getId(), $seccion2->getId()];
        
        $this->client->request('DELETE', '/api/instrumento360/secciones', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode(['seccionIds' => $seccionIds]));
        
        $this->assertResponseIsSuccessful();
        
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('msg', $response);
        $this->assertStringContainsString('eliminaron 2 secciones', $response['msg']);
        
        // Verificar que ambas secciones fueron eliminadas
        $seccion1 = $this->entityManager->getRepository(SeccionEvaluacion360::class)->find($this->testSeccion->getId());
        $seccion2Entity = $this->entityManager->getRepository(SeccionEvaluacion360::class)->find($seccion2->getId());
        
        $this->assertNull($seccion1);
        $this->assertNull($seccion2Entity);
    }

    public function testDeleteSeccionesInvalidData(): void
    {
        $this->client->request('DELETE', '/api/instrumento360/secciones', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode(['invalid' => 'data']));
        
        $this->assertResponseStatusCodeSame(400);
        
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('msg', $response);
        $this->assertStringContainsString('seccionIds', $response['msg']);
    }

    public function testDeleteSeccionOptimizado(): void
    {
        $this->client->request('DELETE', '/api/instrumento360/seccion/' . $this->testSeccion->getId() . '/optimizado');
        
        $this->assertResponseIsSuccessful();
        
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('msg', $response);
        $this->assertStringContainsString('eliminada correctamente', $response['msg']);
    }

    public function testDeleteSeccionesOptimizado(): void
    {
        // Crear una segunda sección para probar eliminación múltiple optimizada
        $seccion2 = new SeccionEvaluacion360();
        $seccion2->setNombre('Sección Test 2');
        $seccion2->setOrden(2);
        $seccion2->setInstrumento($this->testInstrumento);
        $seccion2->setUpdateBy('testuser');
        $seccion2->setIdempresa($this->testInstrumento->getEmpresa());
        $this->entityManager->persist($seccion2);
        $this->entityManager->flush();
        
        $seccionIds = [$this->testSeccion->getId(), $seccion2->getId()];
        
        $this->client->request('DELETE', '/api/instrumento360/secciones/optimizado', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode(['seccionIds' => $seccionIds]));
        
        $this->assertResponseIsSuccessful();
        
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('msg', $response);
        $this->assertStringContainsString('eliminaron 2 secciones', $response['msg']);
    }

    public function testDeleteSeccionesWithMixedErrors(): void
    {
        // Crear una segunda sección
        $seccion2 = new SeccionEvaluacion360();
        $seccion2->setNombre('Sección Test 2');
        $seccion2->setOrden(2);
        $seccion2->setInstrumento($this->testInstrumento);
        $seccion2->setUpdateBy('testuser');
        $seccion2->setIdempresa($this->testInstrumento->getEmpresa());
        $this->entityManager->persist($seccion2);
        $this->entityManager->flush();
        
        // Intentar eliminar secciones válidas + una inexistente
        $seccionIds = [$this->testSeccion->getId(), $seccion2->getId(), 99999];
        
        $this->client->request('DELETE', '/api/instrumento360/secciones', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode(['seccionIds' => $seccionIds]));
        
        $this->assertResponseIsSuccessful();
        
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('msg', $response);
        $this->assertStringContainsString('eliminaron 2 secciones', $response['msg']);
        $this->assertStringContainsString('No existe la sección', $response['msg']);
    }
} 