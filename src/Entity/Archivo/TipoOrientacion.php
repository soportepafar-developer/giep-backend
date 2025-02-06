<?php

namespace App\Entity\Archivo;

use App\Entity\Archivo\HistArchivoContVersion;
use App\Entity\Proyecto\Empresa;
use App\Repository\Archivo\TipoOrientacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoOrientacionRepository::class)
 */
class TipoOrientacion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $nombre_orientacion;

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
     * @ORM\OneToMany(targetEntity=HistArchivoContVersion::class, mappedBy="id_orientacion")
     */
    private $idorientacionhist;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idtipoorientacion")
     */
    private $idempresa;

    public function __construct()
    {
        $this->idorientacionhist = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreOrientacion(): ?string
    {
        return $this->nombre_orientacion;
    }

    public function setNombreOrientacion(string $nombre_orientacion): self
    {
        $this->nombre_orientacion = $nombre_orientacion;

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
    public function getIdorientacionhist(): Collection
    {
        return $this->idorientacionhist;
    }

    public function addIdorientacionhist(HistArchivoContVersion $idorientacionhist): self
    {
        if (!$this->idorientacionhist->contains($idorientacionhist)) {
            $this->idorientacionhist[] = $idorientacionhist;
            $idorientacionhist->setIdOrientacion($this);
        }

        return $this;
    }

    public function removeIdorientacionhist(HistArchivoContVersion $idorientacionhist): self
    {
        if ($this->idorientacionhist->removeElement($idorientacionhist)) {
            // set the owning side to null (unless already changed)
            if ($idorientacionhist->getIdOrientacion() === $this) {
                $idorientacionhist->setIdOrientacion(null);
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
