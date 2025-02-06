<?php

namespace App\Entity\StaExped;

use App\Entity\Cargo;
use App\Repository\StaExped\DocumentosIngresosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentosIngresosRepository::class)
 */
class DocumentosIngresos
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
    private $solicitud_empleo;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $sintesis_curricular;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $copia_cedula;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $constancia_trabajo;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $reg_informacion_fiscal;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $verificacion_ref_laborales;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $certificacion_declaracion_jurada;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $licencia;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $certificado_medico;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $punto_cuenta;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $poseer_titulo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion_cargo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */

    private $id_cargo;

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
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $updateBy;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $id_confidencialidad;

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

    public function getSolicitudEmpleo(): ?string
    {
        return $this->solicitud_empleo;
    }

    public function setSolicitudEmpleo(string $solicitud_empleo): self
    {
        $this->solicitud_empleo = $solicitud_empleo;

        return $this;
    }

    public function getSintesisCurricular(): ?string
    {
        return $this->sintesis_curricular;
    }

    public function setSintesisCurricular(string $sintesis_curricular): self
    {
        $this->sintesis_curricular = $sintesis_curricular;

        return $this;
    }

    public function getCopiaCedula(): ?string
    {
        return $this->copia_cedula;
    }

    public function setCopiaCedula(string $copia_cedula): self
    {
        $this->copia_cedula = $copia_cedula;

        return $this;
    }

    public function getConstanciaTrabajo(): ?string
    {
        return $this->constancia_trabajo;
    }

    public function setConstanciaTrabajo(string $constancia_trabajo): self
    {
        $this->constancia_trabajo = $constancia_trabajo;

        return $this;
    }

    public function getRegInformacionFiscal(): ?string
    {
        return $this->reg_informacion_fiscal;
    }

    public function setRegInformacionFiscal(string $reg_informacion_fiscal): self
    {
        $this->reg_informacion_fiscal = $reg_informacion_fiscal;

        return $this;
    }

    public function getVerificacionRefLaborales(): ?string
    {
        return $this->verificacion_ref_laborales;
    }

    public function setVerificacionRefLaborales(string $verificacion_ref_laborales): self
    {
        $this->verificacion_ref_laborales = $verificacion_ref_laborales;

        return $this;
    }

    public function getCertificacionDeclaracionJurada(): ?string
    {
        return $this->certificacion_declaracion_jurada;
    }

    public function setCertificacionDeclaracionJurada(string $certificacion_declaracion_jurada): self
    {
        $this->certificacion_declaracion_jurada = $certificacion_declaracion_jurada;

        return $this;
    }

    public function getLicencia(): ?string
    {
        return $this->licencia;
    }

    public function setLicencia(string $licencia): self
    {
        $this->licencia = $licencia;

        return $this;
    }

    public function getCertificadoMedico(): ?string
    {
        return $this->certificado_medico;
    }

    public function setCertificadoMedico(string $certificado_medico): self
    {
        $this->certificado_medico = $certificado_medico;

        return $this;
    }

    public function getPuntoCuenta(): ?string
    {
        return $this->punto_cuenta;
    }

    public function setPuntoCuenta(string $punto_cuenta): self
    {
        $this->punto_cuenta = $punto_cuenta;

        return $this;
    }

    public function getPoseerTitulo(): ?string
    {
        return $this->poseer_titulo;
    }

    public function setPoseerTitulo(string $poseer_titulo): self
    {
        $this->poseer_titulo = $poseer_titulo;

        return $this;
    }

    public function getDescripcionCargo(): ?string
    {
        return $this->descripcion_cargo;
    }

    public function setDescripcionCargo(string $descripcion_cargo): self
    {
        $this->descripcion_cargo = $descripcion_cargo;

        return $this;
    }

    public function getIdCargo(): ?int
    {
        return $this->id_cargo;
    }

    public function setIdCargo(int $id_cargo): self
    {
        $this->id_cargo = $id_cargo;

        return $this;
    }

    public function getIdDepartamento(): ?int
    {
        return $this->id_departamento;
    }

    public function setIdDepartamento(int $id_departamento): self
    {
        $this->id_departamento = $id_departamento;

        return $this;
    }

    public function getIdArea(): ?int
    {
        return $this->id_area;
    }

    public function setIdArea(?int $id_area): self
    {
        $this->id_area = $id_area;

        return $this;
    }

    public function getIdRegion(): ?int
    {
        return $this->id_region;
    }

    public function setIdRegion(int $id_region): self
    {
        $this->id_region = $id_region;

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

    public function getIdConfidencialidad(): ?string
    {
        return $this->id_confidencialidad;
    }

    public function setIdConfidencialidad(string $id_confidencialidad): self
    {
        $this->id_confidencialidad = $id_confidencialidad;

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
