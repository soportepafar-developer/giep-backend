<?php

namespace App\Entity\Archivo;

use App\Entity\Proyecto\Empresa;
use App\Repository\Archivo\TipoLimitedBloqueoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoLimitedBloqueoRepository::class)
 */
class TipoLimitedBloqueo
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
    private $nombre_bloqueo;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $accion;

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
     * @ORM\OneToMany(targetEntity=Archivos::class, mappedBy="id_limited_bloqueo")
     */
    private $idarchivolimitedbloqueo;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idtipolimitedbloqueo")
     */
    private $idempresa;

    public function __construct()
    {
        $this->idarchivolimitedbloqueo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreBloqueo(): ?string
    {
        return $this->nombre_bloqueo;
    }

    public function setNombreBloqueo(string $nombre_bloqueo): self
    {
        $this->nombre_bloqueo = $nombre_bloqueo;

        return $this;
    }

    public function getAccion(): ?string
    {
        return $this->accion;
    }

    public function setAccion(string $accion): self
    {
        $this->accion = $accion;

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
     * @return Collection|Archivos[]
     */
    public function getIdarchivolimitedbloqueo(): Collection
    {
        return $this->idarchivolimitedbloqueo;
    }

    public function addIdarchivolimitedbloqueo(Archivos $idarchivolimitedbloqueo): self
    {
        if (!$this->idarchivolimitedbloqueo->contains($idarchivolimitedbloqueo)) {
            $this->idarchivolimitedbloqueo[] = $idarchivolimitedbloqueo;
            $idarchivolimitedbloqueo->setIdLimitedBloqueo($this);
        }

        return $this;
    }

    public function removeIdarchivolimitedbloqueo(Archivos $idarchivolimitedbloqueo): self
    {
        if ($this->idarchivolimitedbloqueo->removeElement($idarchivolimitedbloqueo)) {
            // set the owning side to null (unless already changed)
            if ($idarchivolimitedbloqueo->getIdLimitedBloqueo() === $this) {
                $idarchivolimitedbloqueo->setIdLimitedBloqueo(null);
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
