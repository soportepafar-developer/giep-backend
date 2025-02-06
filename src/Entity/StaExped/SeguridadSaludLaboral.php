<?php

namespace App\Entity\StaExped;

use App\Repository\StaExped\SeguridadSaludLaboralRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeguridadSaludLaboralRepository::class)
 */
class SeguridadSaludLaboral
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

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
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $updateBy;

     /**
     * @ORM\Column(type="integer" , nullable=true)
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdDatosPersonales(): ?int
    {
        return $this->id_datos_personales;
    }

    public function setIdDatosPersonales(int $id_datos_personales): self
    {
        $this->id_datos_personales = $id_datos_personales;

        return $this;
    }

    public function getRutaMetro(): ?string
    {
        return $this->ruta_metro;
    }

    public function setRutaMetro(string $ruta_metro): self
    {
        $this->ruta_metro = $ruta_metro;

        return $this;
    }

    public function getAnalisisSeguroTrabajo(): ?string
    {
        return $this->analisis_seguro_trabajo;
    }

    public function setAnalisisSeguroTrabajo(string $analisis_seguro_trabajo): self
    {
        $this->analisis_seguro_trabajo = $analisis_seguro_trabajo;

        return $this;
    }

    public function getEntregaEquipoProteccion(): ?string
    {
        return $this->entrega_equipo_proteccion;
    }

    public function setEntregaEquipoProteccion(string $entrega_equipo_proteccion): self
    {
        $this->entrega_equipo_proteccion = $entrega_equipo_proteccion;

        return $this;
    }

    public function getConstanciaExamenesOcupacionales(): ?string
    {
        return $this->constancia_examenes_ocupacionales;
    }

    public function setConstanciaExamenesOcupacionales(string $constancia_examenes_ocupacionales): self
    {
        $this->constancia_examenes_ocupacionales = $constancia_examenes_ocupacionales;

        return $this;
    }

    public function getConstanciaNormasSeguridad(): ?string
    {
        return $this->constancia_normas_seguridad;
    }

    public function setConstanciaNormasSeguridad(string $constancia_normas_seguridad): self
    {
        $this->constancia_normas_seguridad = $constancia_normas_seguridad;

        return $this;
    }

    public function getCopiaRegistroDelegado(): ?string
    {
        return $this->copia_registro_delegado;
    }

    public function setCopiaRegistroDelegado(string $copia_registro_delegado): self
    {
        $this->copia_registro_delegado = $copia_registro_delegado;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(?\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getCreateBy(): ?string
    {
        return $this->createBy;
    }

    public function setCreateBy(?string $createBy): self
    {
        $this->createBy = $createBy;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getUpdateBy(): ?string
    {
        return $this->updateBy;
    }

    public function setUpdateBy(string $updateBy): self
    {
        $this->updateBy = $updateBy;

        return $this;
    }

    public function getIdempresa(): ?int
    {
        return $this->idempresa;
    }

    public function setIdempresa(int $idempresa): self
    {
        $this->idempresa = $idempresa;

        return $this;
    }


}
