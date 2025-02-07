<?php

namespace App\Repository\Encuesta;

use App\Dto\Encuesta\PreguntaOutPutDto;
use App\Entity\Encuesta\Pregunta;
use App\Entity\Encuesta\TipoCategoria;
use App\Entity\Encuesta\TipoInput;
use App\Entity\Encuesta\OpcionesCargo;
use App\Entity\Encuesta\InstrumentoCaptura;
use App\Entity\Encuesta\Opciones;
use App\Entity\User;
use App\Entity\Status;
use App\Entity\Encuesta\Seccion;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use    Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;


/**
 * @method Pregunta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pregunta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pregunta[]    findAll()
 * @method Pregunta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PreguntaRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, Pregunta::class);
    }


    public function findAllPage($data)
    {
        if ($data['page'] != 0 && $data['page'] != 1) {
            $offset = ($data['page'] - 1) * $data['rowByPage'];
        }
        $query = $this->createQueryBuilder('a');
        $query->orderBy('a.id', 'ASC');
        if ($data['word'] != null) {
            $query->where("a.pregunta like '%" . $data['word'] . "%' or a.orden like '%" . $data['word'] . "%'");
        }
        $query->orderBy('a.id', 'ASC');
        $query->getQuery();

        $paginatorTotalCount = new Paginator($query);
        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult($data['rowByPage'] * ($data['page'] - 1))
            ->setMaxResults($data['rowByPage']);
        $dataUser = array();
        $hijos = [];
        $rolesUser = [];
        $dataPregunta = array();
        foreach ($paginator as $clave => $valor) {
            $preguntaDto = new PreguntaOutPutDto();
            $preguntaDto->id = $valor->getId();
            $preguntaDto->pregunta = $valor->getPregunta();
            $preguntaDto->class = $valor->getClass();
            $preguntaDto->obligatorio = $valor->getObligatorio();
            $preguntaDto->orden = $valor->getOrden();
            $preguntaDto->idInput = ($valor->getIdInput() != null) ? array("id" => $valor->getIdInput()->getId(), "Descripcion" => $valor->getIdInput()->getNombre()) : [];
            $preguntaDto->IdCategoria = ($valor->getIdCategoria() != null) ? array("id" => $valor->getIdCategoria()->getId(), "Descripcion" => $valor->getIdCategoria()->getNombre()) : [];
            $preguntaDto->puntos = $valor->getPuntos();
            $preguntaDto->idInstrumento = ($valor->getIdInstrumento() != null) ? array("id" => $valor->getIdInstrumento()->getId(), "Descripcion" => $valor->getIdInstrumento()->getNombre()) : [];

            $opciones = [];
            foreach ($valor->getOpciones() as $claveOpciones => $valorOpciones) {

                $opciones[] = array(
                    "id" => $valorOpciones->getId(),
                    "Opcion" => $valorOpciones->getNombre(),
                    "Puntos" => $valorOpciones->getPuntos(),
                    "Correcto" => $valorOpciones->getCorrecta()
                );
            }
            $preguntaDto->opciones = $opciones;
            if ($valor->getCreateAt() != null) {
                $preguntaDto->createAt = $valor->getCreateAt()->format("d/m/Y");
            }
            $preguntaDto->updateBy = $valor->getUpdateBy();
            if ($valor->getUpdateAt() != null) {
                $preguntaDto->updateAt = $valor->getUpdateAt()->format("d/m/Y");
            }
            $preguntaDto->createBy = $valor->getCreateBy();
            $dataPregunta[] = $preguntaDto;
        }
        return array("count" => count($paginatorTotalCount), "data" => $dataPregunta);
    }

    /**
     * Create Pregunta.
     */
    public function post($data, $validator, $helper): JsonResponse
    {
        $entityManager = $this->getEntityManager();
        $entity = $helper->setParametersToEntity(new Pregunta(), $data);
        $errors = $validator->validate($entity);

        if ($errors->count() > 0) {
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            return new JsonResponse($messages, 409);
        } else {
            $currentUser = $entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
            $entity->setCreateBy($currentUser->getUserName());
            $entity->setUpdateBy($currentUser->getUserName());
            // $entityStatus = $entityManager->getRepository(Status::class)->findOneById(1);          
            // $entity->setStatus($entityStatus );           
            $entityTipoCategoria = $entityManager->getRepository(TipoCategoria::class)->findOneById($data["IdCategoria"]);
            $entity->setIdCategoria($entityTipoCategoria);
            $entityTipoInput = $entityManager->getRepository(TipoInput::class)->findOneById($data["idInput"]);
            $entity->setIdInput($entityTipoInput);
            $entityIdInstrumento = $entityManager->getRepository(InstrumentoCaptura::class)->findOneById($data["IdCategoria"]);
            $entity->setIdInstrumento($entityIdInstrumento);
            $entitySeccion = $entityManager->getRepository(Seccion::class)->findOneById($data["seccion"]);
            $entity->setSeccion($entitySeccion);
            foreach ($data["opciones"] as $key => $value) {
                $entityOpcion = $entityManager->getRepository(Opciones::class)->find($value["opcion"]);
                if ($entityOpcion != null) {
                    $entity->addOpcione($entityOpcion);
                }
            }
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa);  
            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg' => 'Registro Creado', 'id' => $entity->getId()], 200);
        }
    }



    /**
     * Update Modulo.
     */
    public function put($data, $id, $validator, $helper): JsonResponse
    {

        $entityManager = $this->getEntityManager();
        $entity = $entityManager->getRepository(Pregunta::class)->find($id);
        if (!$entity) {
            return new JsonResponse(['msg' => 'No existen Registros con el id: ' . $id], 404);
        }
        $entity = $helper->setParametersToEntity($entity, $data);
        $currentUser = $entityManager->getRepository(User::class)->find($this->security->getUser()->getId());
        $entity->setUpdateBy($currentUser->getUserName());
        $entity->setUpdateAt(new \DateTime());
        $entityTipoCategoria = $entityManager->getRepository(TipoCategoria::class)->findOneById($data["IdCategoria"]);
        $entity->setIdCategoria($entityTipoCategoria);
        $entitySeccion = $entityManager->getRepository(Seccion::class)->findOneById($data["seccion"]);
        $entity->setSeccion($entitySeccion);
        $entityTipoInput = $entityManager->getRepository(TipoInput::class)->findOneById($data["idInput"]);
        $entity->setIdInput($entityTipoInput);
        $entityIdInstrumento = $entityManager->getRepository(InstrumentoCaptura::class)->findOneById($data["IdCategoria"]);
        $entity->setIdInstrumento($entityIdInstrumento);
        foreach ($data["opciones"] as $key => $value) {
            $entityOpcion = $entityManager->getRepository(Opciones::class)->find($value["opcion"]);
            if ($entityOpcion != null) {
                $entity->addOpcione($entityOpcion);
            }
        }

        $errors = $validator->validate($entity);
        if ($errors->count() > 0) {
            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            return new JsonResponse($messages, 500);
        } else {
            $empresa= $entityManager->getRepository(Empresa::class)->find($this->security->getUser()->getIdempresa());
            if($empresa)
                $entity->setIdempresa($empresa);  
            $entityManager->persist($entity);
            $entityManager->flush();
            return new JsonResponse(['msg' => 'Registro Actualizado: ' . $entity->getId()], 200);
        }
    }


    public function findById($id)
    {
        $userData = $this->createQueryBuilder('a')
            ->andWhere('a.id=' . $id)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
        $dataPregunta = [];
        $opciones = [];
        foreach ($userData as $clave => $valor) {
            $preguntaDto = new PreguntaOutPutDto();
            $preguntaDto->id = $valor->getId();
            $preguntaDto->pregunta = $valor->getPregunta();
            $preguntaDto->class = $valor->getClass();
            $preguntaDto->obligatorio = $valor->getObligatorio();
            $preguntaDto->orden = $valor->getOrden();
            $preguntaDto->idInput = ($valor->getIdInput() != null) ? array("id" => $valor->getIdInput()->getId(), "Descripcion" => $valor->getIdInput()->getNombre()) : [];
            $preguntaDto->IdCategoria = ($valor->getIdCategoria() != null) ? array("id" => $valor->getIdCategoria()->getId(), "Descripcion" => $valor->getIdCategoria()->getNombre()) : [];
            $preguntaDto->puntos = $valor->getPuntos();
            $preguntaDto->idInstrumento = ($valor->getIdInstrumento() != null) ? array("id" => $valor->getIdInstrumento()->getId(), "Descripcion" => $valor->getIdInstrumento()->getNombre()) : [];
            $opciones = [];
            foreach ($valor->getOpciones() as $claveOpciones => $valorOpciones) {

                $opciones[] = array(
                    "id" => $valorOpciones->getId(),
                    "Opcion" => $valorOpciones->getNombre(),
                    "Puntos" => $valorOpciones->getPuntos(),
                    "Correcto" => $valorOpciones->getCorrecta()
                );
            }
            $preguntaDto->opciones = $opciones;
            if ($valor->getCreateAt() != null) {
                $preguntaDto->createAt = $valor->getCreateAt()->format("d/m/Y");
            }
            $preguntaDto->updateBy = $valor->getUpdateBy();
            if ($valor->getUpdateAt() != null) {
                $preguntaDto->updateAt = $valor->getUpdateAt()->format("d/m/Y");
            }
            $preguntaDto->createBy = $valor->getCreateBy();
            $dataPregunta[] = $preguntaDto;
        }
        return $dataPregunta;
    }

    public function findByIdEncuesta($id)
    {

        $entity = $this->getEntityManager()->createQueryBuilder();
        $data = $entity->select("p,q")
            ->from("App\Entity\Encuesta\Pregunta", "p")
            ->innerJoin('p.idInstrumento', 'q')
            ->where("q.id ='" . $id . "'")
            ->getQuery()
            ->getResult();
        $dataPregunta = [];
        $opciones = [];
        foreach ($data as $clave => $valor) {
            $preguntaDto = new PreguntaOutPutDto();
            $preguntaDto->id = $valor->getId();
            $preguntaDto->pregunta = $valor->getPregunta();
            $preguntaDto->class = $valor->getClass();
            $preguntaDto->obligatorio = $valor->getObligatorio();
            $preguntaDto->orden = $valor->getOrden();
            $preguntaDto->idInput = ($valor->getIdInput() != null) ? array("id" => $valor->getIdInput()->getId(), "Descripcion" => $valor->getIdInput()->getNombre()) : [];
            $preguntaDto->IdCategoria = ($valor->getIdCategoria() != null) ? array("id" => $valor->getIdCategoria()->getId(), "Descripcion" => $valor->getIdCategoria()->getNombre()) : [];
            $preguntaDto->puntos = $valor->getPuntos();
            $preguntaDto->idInstrumento = ($valor->getIdInstrumento() != null) ? array("id" => $valor->getIdInstrumento()->getId(), "Descripcion" => $valor->getIdInstrumento()->getNombre()) : [];
            $opciones = [];
            foreach ($valor->getOpciones() as $claveOpciones => $valorOpciones) {

                $opciones[] = array(
                    "id" => $valorOpciones->getId(),
                    "Name" => $valorOpciones->getNombre(),
                    "Puntos" => $valorOpciones->getPuntos(),
                    "Valor" => $valorOpciones->getValor(),
                    "Correcto" => $valorOpciones->getCorrecta()
                );
            }
            $preguntaDto->opciones = $opciones;
            if ($valor->getCreateAt() != null) {
                $preguntaDto->createAt = $valor->getCreateAt()->format("d/m/Y");
            }
            $preguntaDto->updateBy = $valor->getUpdateBy();
            if ($valor->getUpdateAt() != null) {
                $preguntaDto->updateAt = $valor->getUpdateAt()->format("d/m/Y");
            }
            $preguntaDto->createBy = $valor->getCreateBy();
            $dataPregunta[] = $preguntaDto;
        }
        return $dataPregunta;
    }


    public function findByIdEncuestaAndSeccion($id, $idseccion)
    {

        $entity = $this->getEntityManager()->createQueryBuilder();
        $data = $entity->select("p,q,r")
            ->from("App\Entity\Encuesta\Pregunta", "p")
            ->innerJoin('p.idInstrumento', 'q')
            ->innerJoin('p.seccion', 'r')
            ->where("q.id ='" . $id . "'")
            ->andWhere("r.id='" . $idseccion . "'")
            ->getQuery()
            ->getResult();

        $dataPregunta = [];
        $opciones = [];
        foreach ($data as $clave => $valor) {
            $preguntaDto = new PreguntaOutPutDto();
            $preguntaDto->id = $valor->getId();
            $preguntaDto->pregunta = $valor->getPregunta();
            $preguntaDto->class = $valor->getClass();
            $preguntaDto->obligatorio = $valor->getObligatorio();
            $preguntaDto->orden = $valor->getOrden();
            $preguntaDto->idInput = ($valor->getIdInput() != null) ? array("id" => $valor->getIdInput()->getId(), "Descripcion" => $valor->getIdInput()->getNombre()) : [];
            $preguntaDto->IdCategoria = ($valor->getIdCategoria() != null) ? array("id" => $valor->getIdCategoria()->getId(), "Descripcion" => $valor->getIdCategoria()->getNombre()) : null;
            $preguntaDto->puntos = $valor->getPuntos();
            $preguntaDto->idInstrumento = ($valor->getIdInstrumento() != null) ? array("id" => $valor->getIdInstrumento()->getId(), "Descripcion" => $valor->getIdInstrumento()->getNombre()) : [];
            $opciones = [];
            foreach ($valor->getOpciones() as $claveOpciones => $valorOpciones) {

                $opciones[] = array(
                    "id" => $valorOpciones->getId(),
                    "Name" => $valorOpciones->getNombre(),
                    "Puntos" => $valorOpciones->getPuntos(),
                    "Valor" => $valorOpciones->getValor(),
                    "Correcto" => $valorOpciones->getCorrecta(),
                    "scoreByCharge" => $this->getOpcionesCargo($valorOpciones)
                );
            }
            $preguntaDto->opciones = $opciones;
            if ($valor->getCreateAt() != null) {
                $preguntaDto->createAt = $valor->getCreateAt()->format("d/m/Y");
            }
            $preguntaDto->updateBy = $valor->getUpdateBy();
            if ($valor->getUpdateAt() != null) {
                $preguntaDto->updateAt = $valor->getUpdateAt()->format("d/m/Y");
            }
            $preguntaDto->createBy = $valor->getCreateBy();
            $dataPregunta[] = $preguntaDto;
        }
        return $dataPregunta;
    }

    private function getOpcionesCargo($opcion)
    {

        //$sql = " select * from opciones_cargo where opcion_id  = " . $opcion->getId();
        $sql = " select * from opciones_cargo oc
        inner join cargo c on oc.id_cargo_id  = c.id
        where oc.opcion_id  = " . $opcion->getId();
        $opciones = [];
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $entityOpcionesCargo= $stmt->fetchAll();
        if(count($entityOpcionesCargo)>=1)
            foreach ($entityOpcionesCargo as $claveOpciones => $valorOpciones) {
                $opciones[]=array("id_cargo"=>$valorOpciones["id_cargo_id"],
                "label"=>$valorOpciones["descripcion"],
                "score"=>$valorOpciones["score"]
                );
            }
        return $opciones;
    }

    public function delete($id, $validator): JsonResponse
    {
        $entityManager = $this->getEntityManager();
        $entityPregunta = $entityManager->getRepository(Pregunta::class)->find($id);
        if (!$entityPregunta) {
            return new JsonResponse(['msg' => 'No existen Registros con el id: ' . $id], 404);
        }
        if (count($entityPregunta->getRespuestas()) > 0) {
            return new JsonResponse(['msg' => 'No se puede eliminar la pregunta tiene respuesta vinculada: ' . $id], 409);
        }

        foreach ($entityPregunta->getOpciones() as $opciones) {
            $entityManager->remove($opciones);
            $entityManager->flush();
        }
        $entityManager->remove($entityPregunta);
        $entityManager->flush();
        return new JsonResponse(['msg' => 'Registro Eliminado: ' . $entityPregunta->getId()], 200);
    }
}
