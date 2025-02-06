<?php

namespace App\Entity\Proyecto;

use App\Repository\Proyecto\AccionTrazaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccionTrazaRepository::class)
 */
class AccionTraza
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    /**
     * @ORM\OneToMany(targetEntity=Traza::class, mappedBy="accion")
     */
    private $trazas;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idacciontraza")
     */
    private $idempresa;

    public function __construct()
    {
        $this->trazas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Traza[]
     */
    public function getTrazas(): Collection
    {
        return $this->trazas;
    }

    public function addTraza(Traza $traza): self
    {
        if (!$this->trazas->contains($traza)) {
            $this->trazas[] = $traza;
            $traza->setAccion($this);
        }

        return $this;
    }

    public function removeTraza(Traza $traza): self
    {
        if ($this->trazas->removeElement($traza)) {
            // set the owning side to null (unless already changed)
            if ($traza->getAccion() === $this) {
                $traza->setAccion(null);
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
