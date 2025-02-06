<?php

namespace App\Entity\Encuesta;

use App\Entity\Nivel;
use App\Entity\Proyecto\Empresa;
use App\Repository\Encuesta\CategoriaNivelPonderacionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriaNivelPonderacionRepository::class)
 */
class CategoriaNivelPonderacion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TipoCategoria::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoria;

    /**
     * @ORM\ManyToOne(targetEntity=Nivel::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $nivel;

    /**
     * @ORM\Column(type="integer")
     */
    private $ponderacion;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idcategorianivelponderacion")
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNivel(): ?Nivel
    {
        return $this->nivel;
    }

    public function setNivel(?Nivel $nivel): self
    {
        $this->nivel = $nivel;

        return $this;
    }

    public function getPonderacion(): ?int
    {
        return $this->ponderacion;
    }

    public function setPonderacion(int $ponderacion): self
    {
        $this->ponderacion = $ponderacion;

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
