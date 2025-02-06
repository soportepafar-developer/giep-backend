<?php

namespace App\Entity\Archivo;

use App\Entity\Proyecto\Empresa;
use App\Repository\Archivo\ArchivosExtesionesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArchivosExtesionesRepository::class)
 */
class ArchivosExtesiones
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TipoArchivo::class, inversedBy="tipo_archivo_extesiones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_tipo_archivos;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $tipo_extesiones;

    /**
     * @ORM\ManyToOne(targetEntity=TipoStatusArchivo::class, inversedBy="id_tipo_status_extensiones")
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
     * @ORM\OneToMany(targetEntity=TamanoArchivoPermitido::class, mappedBy="id_tipo_archivo_ext")
     */
    private $id_ext_tamano_permitido;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idarchivosextensiones")
     */
    private $idempresa;


    public function __construct()
    {
        $this->id_ext_tamano_permitido = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdTipoArchivos(): ?TipoArchivo
    {
        return $this->id_tipo_archivos;
    }

    public function setIdTipoArchivos(?TipoArchivo $id_tipo_archivos): self
    {
        $this->id_tipo_archivos = $id_tipo_archivos;

        return $this;
    }

    public function getTipoExtesiones(): ?string
    {
        return $this->tipo_extesiones;
    }

    public function setTipoExtesiones(string $tipo_extesiones): self
    {
        $this->tipo_extesiones = $tipo_extesiones;

        return $this;
    }

    public function getIdStatusTipoArchivo(): ?TipoStatusArchivo
    {
        return $this->id_status_tipo_archivo;
    }

    public function setIdStatusTipoArchivo(?TipoStatusArchivo $id_status_tipo_archivo): self
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

    /**
     * @return Collection|TamanoArchivoPermitido[]
     */
    public function getIdExtTamanoPermitido(): Collection
    {
        return $this->id_ext_tamano_permitido;
    }

    public function addIdExtTamanoPermitido(TamanoArchivoPermitido $idExtTamanoPermitido): self
    {
        if (!$this->id_ext_tamano_permitido->contains($idExtTamanoPermitido)) {
            $this->id_ext_tamano_permitido[] = $idExtTamanoPermitido;
            $idExtTamanoPermitido->setIdTipoArchivoExt($this);
        }

        return $this;
    }

    public function removeIdExtTamanoPermitido(TamanoArchivoPermitido $idExtTamanoPermitido): self
    {
        if ($this->id_ext_tamano_permitido->removeElement($idExtTamanoPermitido)) {
            // set the owning side to null (unless already changed)
            if ($idExtTamanoPermitido->getIdTipoArchivoExt() === $this) {
                $idExtTamanoPermitido->setIdTipoArchivoExt(null);
            }
        }

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
