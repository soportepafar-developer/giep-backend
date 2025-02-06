<?php

namespace App\Dto\StaExped;
use App\Repository\StaExped\ReposoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReposoRepository::class)
 */
class ReposoOutPutDto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_datos_personales;

     /**
     * @ORM\Column(type="integer")
     */
    private $id_tiporeposo;

     /**
     * @ORM\Column(type="integer")
     */
    private $id_motivo_reposo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_desde;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_hasta;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $createBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $updateBy;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdDatosPersonales(): ?int
    {
        return $this->id_datos_personales;
    }

    public function getIdTiporeposo(): ?int
    {
        return $this->id_tiporeposo;
    }

    public function getIdMotivoReposo(): ?int
    {
        return $this->id_motivo_reposo;
    }

    public function getFechaDesde(): ?\DateTimeInterface
    {
        return $this->fecha_desde;
    }

    public function getFechaHasta(): ?\DateTimeInterface
    {
        return $this->fecha_hasta;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function getCreateBy(): ?string
    {
        return $this->createBy;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function getUpdateBy(): ?string
    {
        return $this->updateBy;
    }

}
