<?php

namespace App\Repository\Instrumento360;

use App\Dto\Instrumento360\PreguntaEvaluacion360Dto;
use App\Entity\Instrumento360\PreguntaEvaluacion360;
//use App\Entity\Instrumento360\TipoCategoriaEvaluacion;
use App\Entity\Instrumento360\TipoInputEvaluacion360;
//use App\Entity\Instrumento360\OpcionesCargoEvaluacion;
//use App\Entity\Instrumento360\Evaluacion;
use App\Entity\Instrumento360\OpcionesEvaluacion360;
use App\Entity\User;
use App\Entity\Status;
use App\Entity\Instrumento360\SeccionEvaluacion360;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Proyecto\Empresa;


/**
 * @method PreguntaEvaluacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method PreguntaEvaluacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method PreguntaEvaluacion[]    findAll()
 * @method PreguntaEvaluacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PreguntaEvaluacion360Repository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        $this->security = $security;
        parent::__construct($registry, PreguntaEvaluacion360::class);
    }


    public function findByIdEncuestaAndSeccion($id, $idseccion)
    {
        


        $entity = $this->getEntityManager()->createQueryBuilder();
        $data = $entity->select("p,q,r")
            ->from("App\Entity\Instrumento360\PreguntaEvaluacion360", "p")
            ->innerJoin('p.idInstrumento', 'q')
            ->innerJoin('p.seccion', 'r')
            ->where("q.id ='" . $id . "'")
            ->andWhere("r.id='" . $idseccion . "'")
            ->getQuery()
            ->getResult();

        $dataPregunta = [];
        $opciones = [];
        foreach ($data as $clave => $valor) {
            $preguntaDto = new PreguntaEvaluacion360Dto();
            $preguntaDto->id = $valor->getId();
            $preguntaDto->pregunta = $valor->getPregunta();
            $preguntaDto->class = $valor->getClass();
            $preguntaDto->obligatorio = $valor->getObligatorio();
            $preguntaDto->orden = $valor->getOrden();
            $preguntaDto->idInput = ($valor->getIdInput() != null) ? array("id" => $valor->getIdInput()->getId(), "Descripcion" => $valor->getIdInput()->getNombre()) : [];
            $preguntaDto->Competencia360 = ($valor->getIdCategoria() != null) ? array("id" => $valor->getIdCategoria()->getId(), "Descripcion" => $valor->getIdCategoria()->getNombre()) : null;
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



}
