<?php

namespace App\Entity\DocumentoDigital;

use App\Entity\DocumentoDigital\HistArchivoContVersiond;
use App\Entity\Proyecto\Empresa;
use App\Repository\DocumentoDigital\TipoOrientaciondRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoOrientaciondRepository::class)
 */
class TipoOrientaciond
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
     * @ORM\Column(type="integer" , nullable=true)
     */
    private $idempresa_id;

    /**
     * @ORM\OneToMany(targetEntity=HistArchivoContVersiond::class, mappedBy="id_orientacion")
     */
    private $idorientacionhist;


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

    public function getIdempresa(): ?int
    {
        return $this->idempresa_id;
    }

    public function setIdempresa(int $idempresa_id): self
    {
        $this->idempresa_id = $idempresa_id;

        return $this;
    }


    /**
     * @return Collection|HistArchivoContVersiond[]
     */
    public function getIdorientacionhist(): Collection
    {
        return $this->idorientacionhist;
    }

    public function addIdorientacionhist(HistArchivoContVersiond $idorientacionhist): self
    {
        if (!$this->idorientacionhist->contains($idorientacionhist)) {
            $this->idorientacionhist[] = $idorientacionhist;
            $idorientacionhist->setIdOrientacion($this);
        }

        return $this;
    }

    public function removeIdorientacionhist(HistArchivoContVersiond $idorientacionhist): self
    {
        if ($this->idorientacionhist->removeElement($idorientacionhist)) {
            // set the owning side to null (unless already changed)
            if ($idorientacionhist->getIdOrientacion() === $this) {
                $idorientacionhist->setIdOrientacion(null);
            }
        }

        return $this;
    }



}
