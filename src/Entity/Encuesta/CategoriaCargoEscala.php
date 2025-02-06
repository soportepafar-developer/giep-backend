<?php

namespace App\Entity\Encuesta;

use App\Entity\Cargo;
use App\Entity\Proyecto\Empresa;
use App\Repository\Encuesta\CategoriaCargoEscalaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriaCargoEscalaRepository::class)
 */
class CategoriaCargoEscala
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Cargo::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $cargo;

    /**
     * @ORM\ManyToOne(targetEntity=TipoCategoria::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoria;

    /**
     * @ORM\Column(type="integer")
     */
    private $escala;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idcategoriacargoescala")
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCargo(): ?Cargo
    {
        return $this->cargo;
    }

    public function setCargo(?Cargo $cargo): self
    {
        $this->cargo = $cargo;

        return $this;
    }

    public function getCategoria(): ?TipoCategoria
    {
        return $this->categoria;
    }

    public function setCategoria(?TipoCategoria $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getEscala(): ?int
    {
        return $this->escala;
    }

    public function setEscala(int $escala): self
    {
        $this->escala = $escala;

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
