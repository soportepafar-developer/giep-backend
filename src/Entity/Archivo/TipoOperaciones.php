<?php

namespace App\Entity\Archivo;

use App\Entity\Archivo\HistArchivoContVersion;
use App\Entity\Proyecto\Empresa;
use App\Repository\Archivo\TipoOperacionesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoOperacionesRepository::class)
 */
class TipoOperaciones
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $operacion;

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
     * @ORM\OneToMany(targetEntity=HistArchivoContVersion::class, mappedBy="id_tipo_operaciones")
     */
    private $idoperaciones_hist;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idtipooperaciones")
     */
    private $idempresa;

    public function __construct()
    {
    //    $this->iduser_operaciones_archivo = new ArrayCollection();
        $this->idoperaciones_hist = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOperacion(): ?string
    {
        return $this->operacion;
    }

    public function setOperacion(string $operacion): self
    {
        $this->operacion = $operacion;

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
     * @return Collection|HistArchivoContVersion[]
     */
    public function getIdoperacionesHist(): Collection
    {
        return $this->idoperaciones_hist;
    }

    public function addIdoperacionesHist(HistArchivoContVersion $idoperacionesHist): self
    {
        if (!$this->idoperaciones_hist->contains($idoperacionesHist)) {
            $this->idoperaciones_hist[] = $idoperacionesHist;
            $idoperacionesHist->setIdTipoOperaciones($this);
        }

        return $this;
    }

    public function removeIdoperacionesHist(HistArchivoContVersion $idoperacionesHist): self
    {
        if ($this->idoperaciones_hist->removeElement($idoperacionesHist)) {
            // set the owning side to null (unless already changed)
            if ($idoperacionesHist->getIdTipoOperaciones() === $this) {
                $idoperacionesHist->setIdTipoOperaciones(null);
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
