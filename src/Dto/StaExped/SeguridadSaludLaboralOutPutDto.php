<?php

namespace App\Dto\StaExped;
use App\Repository\StaExped\SeguridadSaludLaboralRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeguridadSaludLaboralRepository::class)
 */
class SeguridadSaludLaboralOutPutDto
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
     * @ORM\Column(type="string", length=2)
     */
    private $ruta_metro;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $analisis_seguro_trabajo;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $entrega_equipo_proteccion;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $constancia_examenes_ocupacionales;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $constancia_normas_seguridad;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $copia_registro_delegado;

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

    public function getRutaMetro(): ?string
    {
        return $this->ruta_metro;
    }

    public function getAnalisisSeguroTrabajo(): ?string
    {
        return $this->analisis_seguro_trabajo;
    }

    public function getEntregaEquipoProteccion(): ?string
    {
        return $this->entrega_equipo_proteccion;
    }

    public function getConstanciaExamenesOcupacionales(): ?string
    {
        return $this->constancia_examenes_ocupacionales;
    }

    public function getConstanciaNormasSeguridad(): ?string
    {
        return $this->constancia_normas_seguridad;
    }

    public function getCopiaRegistroDelegado(): ?string
    {
        return $this->copia_registro_delegado;
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
