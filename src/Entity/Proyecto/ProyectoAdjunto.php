<?php

namespace App\Entity\Proyecto;

use App\Entity\User;
use App\Repository\Proyecto\ProyectoAdjuntoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProyectoAdjuntoRepository::class)
 */
class ProyectoAdjunto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private $path;

    /**
     * @ORM\ManyToOne(targetEntity=Proyecto::class, inversedBy="proyectoAdjuntos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idProyecto;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="proyectoAdjuntos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $IdUser;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idproyectoadjunto")
     */
    private $idempresa;

   public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getIdProyecto(): ?Proyecto
    {
        return $this->idProyecto;
    }

    public function setIdProyecto(?Proyecto $idProyecto): self
    {
        $this->idProyecto = $idProyecto;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->IdUser;
    }

    public function setIdUser(?User $IdUser): self
    {
        $this->IdUser = $IdUser;

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
