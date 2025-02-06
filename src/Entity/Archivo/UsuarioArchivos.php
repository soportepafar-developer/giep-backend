<?php

namespace App\Entity\Archivo;

use App\Entity\Proyecto\Empresa;
use App\Entity\User;
use App\Repository\Archivo\UsuarioArchivosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsuarioArchivosRepository::class)
 */
class UsuarioArchivos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="idusuriosarchivos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idusuario;

    /**
     * @ORM\ManyToOne(targetEntity=Archivos::class, inversedBy="iduserarchivos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $iduserarchivos;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idusuarioarchivos")
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdusuario(): ?User
    {
        return $this->idusuario;
    }

    public function setIdusuario(?User $idusuario): self
    {
        $this->idusuario = $idusuario;

        return $this;
    }

    public function getIduserarchivos(): ?Archivos
    {
        return $this->iduserarchivos;
    }

    public function setIduserarchivos(?Archivos $iduserarchivos): self
    {
        $this->iduserarchivos = $iduserarchivos;

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
