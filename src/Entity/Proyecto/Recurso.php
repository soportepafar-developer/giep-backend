<?php

namespace App\Entity\Proyecto;

use App\Entity\User;
use App\Repository\Proyecto\RecursoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecursoRepository::class)
 */
class Recurso
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Proyecto::class, inversedBy="yes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $proyectoId;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idrecurso")
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProyectoId(): ?Proyecto
    {
        return $this->proyectoId;
    }

    public function setProyectoId(?Proyecto $proyectoId): self
    {
        $this->proyectoId = $proyectoId;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

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
