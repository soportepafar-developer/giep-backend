<?php

namespace App\Entity\Archivo;

use App\Entity\Proyecto\Empresa;
use App\Repository\Archivo\TamanoArchivoPermitidoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TamanoArchivoPermitidoRepository::class)
 */
class TamanoArchivoPermitido
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ArchivosExtesiones::class, inversedBy="id_ext_tamano_permitido")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_tipo_archivo_ext;

    /**
     * @ORM\Column(type="integer")
     */
    private $tamano;

    /**
     * @ORM\ManyToOne(targetEntity=TipoArchivo::class, inversedBy="id_tipo_archivo_tamano")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_status_tipo_archivo;

    /**
     * @ORM\Column(type="integer")
     */
    private $sw_cont_version;

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
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idtamanoarchivopermitido")
     */
    private $idempresa;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdTipoArchivoExt(): ?ArchivosExtesiones
    {
        return $this->id_tipo_archivo_ext;
    }

    public function setIdTipoArchivoExt(?ArchivosExtesiones $id_tipo_archivo_ext): self
    {
        $this->id_tipo_archivo_ext = $id_tipo_archivo_ext;

        return $this;
    }

    public function getTamano(): ?int
    {
        return $this->tamano;
    }

    public function setTamano(int $tamano): self
    {
        $this->tamano = $tamano;

        return $this;
    }

    public function getIdStatusTipoArchivo(): ?TipoArchivo
    {
        return $this->id_status_tipo_archivo;
    }

    public function setIdStatusTipoArchivo(?TipoArchivo $id_status_tipo_archivo): self
    {
        $this->id_status_tipo_archivo = $id_status_tipo_archivo;

        return $this;
    }

    public function getSwContVersion(): ?int
    {
        return $this->sw_cont_version;
    }

    public function setSwContVersion(int $sw_cont_version): self
    {
        $this->sw_cont_version = $sw_cont_version;

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

    public function getIdempresa(): ?Empresa
    {
        return $this->idempresa;
    }

    public function setIdempresa(?Empresa $idempresa): self
    {
        $this->idempresa = $idempresa;

        return $this;
    }

}
