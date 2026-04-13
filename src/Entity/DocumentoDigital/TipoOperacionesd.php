<?php

namespace App\Entity\DocumentoDigital;

use App\Entity\DocumentoDigital\HistArchivoContVersiond;
use App\Entity\Proyecto\Empresa;
use App\Repository\DocumentoDigital\TipoOperacionesdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoOperacionesdRepository::class)
 */
class TipoOperacionesd
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
     * @ORM\Column(type="integer" , nullable=true)
     */
    private $idempresa_id;

    /**
     * @ORM\OneToMany(targetEntity=HistArchivoContVersiond::class, mappedBy="id_tipo_operaciones")
     */
    private $idoperaciones_hist;


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
    public function getIdoperacionesHist(): Collection
    {
        return $this->idoperaciones_hist;
    }

    public function addIdoperacionesHist(HistArchivoContVersiond $idoperacionesHist): self
    {
        if (!$this->idoperaciones_hist->contains($idoperacionesHist)) {
            $this->idoperaciones_hist[] = $idoperacionesHist;
            $idoperacionesHist->setIdTipoOperaciones($this);
        }

        return $this;
    }

    public function removeIdoperacionesHist(HistArchivoContVersiond $idoperacionesHist): self
    {
        if ($this->idoperaciones_hist->removeElement($idoperacionesHist)) {
            // set the owning side to null (unless already changed)
            if ($idoperacionesHist->getIdTipoOperaciones() === $this) {
                $idoperacionesHist->setIdTipoOperaciones(null);
            }
        }

        return $this;
    }


}
