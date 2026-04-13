<?php

namespace App\Entity\Instrumento360;

use App\Repository\Instrumento360\RangosOdisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RangosOdisRepository::class)
 */
class RangosOdis
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
    private $rango;

        /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $porcentaje;

    /**
     * @ORM\OneToMany(targetEntity=OdisObjetivos::class, mappedBy="odirango", orphanRemoval=true)
     */
    private $odisObjetivos;

    public function __construct()
    {
        $this->odisObjetivos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRango(): ?int
    {
        return $this->rango;
    }

    public function setRango(int $rango): self
    {
        $this->rango = $rango;

        return $this;
    }

    public function getDescripcion(): ?string   
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;   
    }
    
    public function getPorcentaje(): ?float
    {
        return $this->porcentaje;
    }

    public function setPorcentaje(?float $porcentaje): self
    {
        $this->porcentaje = $porcentaje;

        return $this;
    }

    /**
     * @return Collection|OdisObjetivos[]
     */
    public function getOdisObjetivos(): Collection
    {
        return $this->odisObjetivos;
    }

    public function addOdisObjetivo(OdisObjetivos $odisObjetivo): self
    {
        if (!$this->odisObjetivos->contains($odisObjetivo)) {
            $this->odisObjetivos[] = $odisObjetivo;
            $odisObjetivo->setOdirango($this);
        }

        return $this;
    }

    public function removeOdisObjetivo(OdisObjetivos $odisObjetivo): self
    {
        if ($this->odisObjetivos->removeElement($odisObjetivo)) {
            // set the owning side to null (unless already changed)
            if ($odisObjetivo->getOdirango() === $this) {
                $odisObjetivo->setOdirango(null);
            }
        }

        return $this;
    }
}
