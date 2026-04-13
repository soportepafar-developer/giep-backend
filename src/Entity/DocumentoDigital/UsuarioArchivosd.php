<?php

namespace App\Entity\DocumentoDigital;
use App\Entity\Proyecto\Empresa;
use App\Entity\User;
use App\Repository\Archivo\UsuarioArchivosdRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsuarioArchivosdRepository::class)
 */
class UsuarioArchivosd
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

     /**
     * @ORM\Column(type="integer" , nullable=true)
     */
    private $idusuario_id;

    /**
     * @ORM\ManyToOne(targetEntity=Archivosd::class, inversedBy="iduserarchivos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $iduserarchivos;

    /**
     * @ORM\Column(type="integer" , nullable=true)
     */
    private $idempresa_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdusuario(): ?int
    {
        return $this->idusuario_id;
    }

    public function setIdusuario(int $idusuario_id): self
    {
        $this->idusuario_id = $idusuario_id;

        return $this;
    }

    /* public function getIdusuario(): ?User
    {
        return $this->idusuario;
    }

    public function setIdusuario(?User $idusuario): self
    {
        $this->idusuario = $idusuario;

        return $this;
    } */

    public function getIduserarchivos(): ?Archivosd
    {
        return $this->iduserarchivos;
    }

    public function setIduserarchivos(?Archivosd $iduserarchivos): self
    {
        $this->iduserarchivos = $iduserarchivos;

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


}
