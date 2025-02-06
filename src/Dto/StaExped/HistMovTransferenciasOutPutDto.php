<?php
namespace App\Dto\StaExped;
use App\Repository\StaExped\HistMovTransferenciasRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistMovTransferenciasRepository::class)
 */
class HistMovTransferenciasOutPutDto
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
     * @ORM\Column(type="datetime")
     */
    private $fecha_transferencia;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_departamento;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_area;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_region;

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

    public function getFechaTransferencia(): ?\DateTimeInterface
    {
        return $this->fecha_transferencia;
    }

    public function getIdDepartamento(): ?int
    {
        return $this->id_departamento;
    }

    public function getIdArea(): ?int
    {
        return $this->id_area;
    }

    public function getIdRegion(): ?int
    {
        return $this->id_region;
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
