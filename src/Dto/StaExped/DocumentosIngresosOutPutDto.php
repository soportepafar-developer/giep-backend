<?php

namespace App\Dto\StaExped;
use App\Repository\StaExped\DocumentosIngresosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentosIngresosRepository::class)
 */
class DocumentosIngresosOutPutDto
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
     * @ORM\Column(type="string", length=255)
     */
    private $updateBy;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $id_confidencialidad;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdDatosPersonales(): ?int
    {
        return $this->id_datos_personales;
    }

    public function getSolicitudEmpleo(): ?string
    {
        return $this->solicitud_empleo;
    }

    public function getSintesisCurricular(): ?string
    {
        return $this->sintesis_curricular;
    }

    public function getCopiaCedula(): ?string
    {
        return $this->copia_cedula;
    }

    public function getConstanciaTrabajo(): ?string
    {
        return $this->constancia_trabajo;
    }

    public function getRegInformacionFiscal(): ?string
    {
        return $this->reg_informacion_fiscal;
    }

    public function getVerificacionRefLaborales(): ?string
    {
        return $this->verificacion_ref_laborales;
    }

    public function getCertificacionDeclaracionJurada(): ?string
    {
        return $this->certificacion_declaracion_jurada;
    }

    public function getLicencia(): ?string
    {
        return $this->licencia;
    }

    public function getCertificadoMedico(): ?string
    {
        return $this->certificado_medico;
    }

    public function getPuntoCuenta(): ?string
    {
        return $this->punto_cuenta;
    }

    public function getPoseerT�tulo(): ?string
    {
        return $this->poseer_t�tulo;
    }

    public function getDescripcionCargo(): ?string
    {
        return $this->descripcion_cargo;
    }

    public function getIdCargo(): ?int
    {
        return $this->id_cargo;
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

    public function getIdConfidencialidad(): ?string
    {
        return $this->id_confidencialidad;
    }

}
