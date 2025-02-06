<?php

namespace App\Entity\Proyecto;

use App\Entity\User;
use App\Repository\Proyecto\HistMovimientosItemsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistMovimientosItemsRepository::class)
 */
class HistMovimientosItems
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=600)
     */
    private $titulo;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="histMovmtItemsIdUser")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idUser;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\ManyToOne(targetEntity=Items::class)
     */
    private $idBacklogPadre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $peso;

    /**
     * @ORM\ManyToOne(targetEntity=NivelBoardPanel::class, inversedBy="histNivelBoardPanel")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idnivelboardpanel;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class)
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getIdBacklogPadre(): ?Items
    {
        return $this->idBacklogPadre;
    }

    public function setIdBacklogPadre(?Items $idBacklogPadre): self
    {
        $this->idBacklogPadre = $idBacklogPadre;

        return $this;
    }

    public function getPeso(): ?int
    {
        return $this->peso;
    }

    public function setPeso(?int $peso): self
    {
        $this->peso = $peso;

        return $this;
    }

    public function getIdnivelboardpanel(): ?NivelBoardPanel
    {
        return $this->idnivelboardpanel;
    }

    public function setIdnivelboardpanel(?NivelBoardPanel $idnivelboardpanel): self
    {
        $this->idnivelboardpanel = $idnivelboardpanel;

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
    

