<?php

namespace App\Entity\DocumentoDigital;
use App\Entity\DocumentoDigital\SubSerie;
use App\Repository\DocumentoDigital\ControlArchivoDigitalRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ControlArchivoDigitalRepository::class)
 */
class ControlArchivoDigital
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
    private $id_pais;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_estado;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_ciudad;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $asuntos;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_fin_conservac;

    /**
     * @ORM\Column(type="integer")
     */
    private $sw_archivo_fisico;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $argumento_justificacion;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $num_expediente;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_documento;

    /**
     * @ORM\ManyToOne(targetEntity=TipoAlmacen::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $id_tipo_almacen;

    /**
     * @ORM\ManyToOne(targetEntity=Ubicacion::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $idubica1;

    /**
     * @ORM\ManyToOne(targetEntity=Ubicacion::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $idubica2;

    /**
     * @ORM\ManyToOne(targetEntity=Ubicacion::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $idubica3;

    /**
     * @ORM\ManyToOne(targetEntity=Ubicacion::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $idubica4;

    /**
     * @ORM\ManyToOne(targetEntity=SubSerie::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $codigo_serie_subserie;

    /**
     * @ORM\ManyToOne(targetEntity=TipoStatusArchivod::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_status_tipoestado;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad_caja;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_user_entrega;

    /**
     * @ORM\ManyToOne(targetEntity=ContenidoCaja::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $idcontenido_caja;

    /**
     * @ORM\Column(type="integer" , nullable=true)
     */
    private $idempresa_id;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $updateBy;

    /**
     * @ORM\ManyToOne(targetEntity=EstructuraOrganizativa::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_estructura_organizativa;

    /**
     * @ORM\Column(type="integer")
     */

    private $idregion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPais(): ?int
    {
        return $this->id_pais;
    }

    public function setIdPais(int $id_pais): self
    {
        $this->id_pais = $id_pais;

        return $this;
    }

    public function getIdEstado(): ?int
    {
        return $this->id_estado;
    }

    public function setIdEstado(int $id_estado): self
    {
        $this->id_estado = $id_estado;

        return $this;
    }

    public function getIdCiudad(): ?int
    {
        return $this->id_ciudad;
    }

    public function setIdCiudad(int $id_ciudad): self
    {
        $this->id_ciudad = $id_ciudad;

        return $this;
    }

    public function getAsuntos(): ?string
    {
        return $this->asuntos;
    }

    public function setAsuntos(string $asuntos): self
    {
        $this->asuntos = $asuntos;

        return $this;
    }

    public function getFechaFinConservac(): ?\DateTimeInterface
    {
        return $this->fecha_fin_conservac;
    }

    public function setFechaFinConservac(?\DateTimeInterface $fecha_fin_conservac): self
    {
        $this->fecha_fin_conservac = $fecha_fin_conservac;

        return $this;
    }

    public function getSwArchivoFisico(): ?int
    {
        return $this->sw_archivo_fisico;
    }

    public function setSwArchivoFisico(int $sw_archivo_fisico): self
    {
        $this->sw_archivo_fisico = $sw_archivo_fisico;

        return $this;
    }

    public function getArgumentoJustificacion(): ?string
    {
        return $this->argumento_justificacion;
    }

    public function setArgumentoJustificacion(string $argumento_justificacion): self
    {
        $this->argumento_justificacion = $argumento_justificacion;

        return $this;
    }

    public function getNumExpediente(): ?string
    {
        return $this->num_expediente;
    }

    public function setNumExpediente(string $num_expediente): self
    {
        $this->num_expediente = $num_expediente;

        return $this;
    }

    public function getFechaDocumento(): ?\DateTimeInterface
    {
        return $this->fecha_documento;
    }

    public function setFechaDocumento(\DateTimeInterface $fecha_documento): self
    {
        $this->fecha_documento = $fecha_documento;

        return $this;
    }

    public function getIdTipoAlmacen(): ?TipoAlmacen
    {
        return $this->id_tipo_almacen;
    }

    public function setIdTipoAlmacen(?TipoAlmacen $id_tipo_almacen): self
    {
        $this->id_tipo_almacen = $id_tipo_almacen;

        return $this;
    }

    public function getIdubica1(): ?Ubicacion
    {
        return $this->idubica1;
    }

    public function setIdubica1(?Ubicacion $idubica1): self
    {
        $this->idubica1 = $idubica1;

        return $this;
    }

    public function getIdubica2(): ?Ubicacion
    {
        return $this->idubica2;
    }

    public function setIdubica2(?Ubicacion $idubica2): self
    {
        $this->idubica2 = $idubica2;

        return $this;
    }

    public function getIdubica3(): ?Ubicacion
    {
        return $this->idubica3;
    }

    public function setIdubica3(?Ubicacion $idubica3): self
    {
        $this->idubica3 = $idubica3;

        return $this;
    }

    public function getIdubica4(): ?Ubicacion
    {
        return $this->idubica4;
    }

    public function setIdubica4(?Ubicacion $idubica4): self
    {
        $this->idubica4 = $idubica4;

        return $this;
    }

    public function getCodigoSerieSubserie(): ?SubSerie
    {
        return $this->codigo_serie_subserie;
    }

    public function setCodigoSerieSubserie(?SubSerie $codigo_serie_subserie): self
    {
        $this->codigo_serie_subserie = $codigo_serie_subserie;

        return $this;
    }

    public function getIdStatusTipoestado(): ?TipoStatusArchivod
    {
        return $this->id_status_tipoestado;
    }

    public function setIdStatusTipoestado(?TipoStatusArchivod $id_status_tipoestado): self
    {
        $this->id_status_tipoestado = $id_status_tipoestado;

        return $this;
    }

    public function getCantidadCaja(): ?int
    {
        return $this->cantidad_caja;
    }

    public function setCantidadCaja(int $cantidad_caja): self
    {
        $this->cantidad_caja = $cantidad_caja;

        return $this;
    }

    public function getIdUserEntrega(): ?int
    {
        return $this->id_user_entrega;
    }

    public function setIdUserEntrega(int $id_user_entrega): self
    {
        $this->id_user_entrega = $id_user_entrega;

        return $this;
    }

    public function getIdcontenidoCaja(): ?ContenidoCaja
    {
        return $this->idcontenido_caja;
    }

    public function setIdcontenidoCaja(?ContenidoCaja $idcontenido_caja): self
    {
        $this->idcontenido_caja = $idcontenido_caja;

        return $this;
    }

    public function getIdempresa(): ?int
    {
        return $this->idempresa_id;
    }

    public function setIdempresa(int $idempresa_id): self
    {
        $this->idempresa_id = $idempresa_id;

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

    public function setUpdateBy(?string $updateBy): self
    {
        $this->updateBy = $updateBy;

        return $this;
    }

    public function getIdEstructuraOrganizativa(): ?EstructuraOrganizativa
    {
        return $this->id_estructura_organizativa;
    }

    public function setIdEstructuraOrganizativa(?EstructuraOrganizativa $id_estructura_organizativa): self
    {
        $this->id_estructura_organizativa = $id_estructura_organizativa;

        return $this;
    }

    public function getIdregion(): ?int
    {
        return $this->idregion;
    }

    public function setIdregion(int $idregion): self
    {
        $this->idregion = $idregion;

        return $this;
    }

}
