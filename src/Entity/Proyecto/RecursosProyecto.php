<?php

namespace App\Entity\Proyecto;

use App\Entity\User;
use App\Repository\Proyecto\RecursosProyectoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecursosProyectoRepository::class)
 */
class RecursosProyecto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Proyecto::class, inversedBy="idproyectoid")
     */
    private $idproyecto;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="idrecursoid")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idrecurso;

    /**
     * @ORM\Column(type="integer")
     */
    private $horasdedicacion;
    
     /**
     * @ORM\Column(type="integer")
     */
    private $swact;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idrecursosproyecto")
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdproyecto(): ?Proyecto
    {
        return $this->idproyecto;
    }

    public function setIdproyecto(?Proyecto $idproyecto): self
    {
        $this->idproyecto = $idproyecto;

        return $this;
    }

    public function getIdrecurso(): ?User
    {
        return $this->idrecurso;
    }

    public function setIdrecurso(?User $idrecurso): self
    {
        $this->idrecurso = $idrecurso;

        return $this;
    }

    public function getHorasdedicacion(): ?int
    {
        return $this->horasdedicacion;
    }

    public function setHorasdedicacion(int $horasdedicacion): self
    {
        $this->horasdedicacion = $horasdedicacion;

        return $this;
    }
    
    public function getSwact(): ?int
    {
        return $this->swact;
    }

    public function setSwact(int $swact): self
    {
        $this->swact = $swact;

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
