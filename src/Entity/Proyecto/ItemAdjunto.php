<?php

namespace App\Entity\Proyecto;

use App\Entity\User;
use App\Repository\Proyecto\ItemAdjuntoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemAdjuntoRepository::class)
 */
class ItemAdjunto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Items::class, inversedBy="itemAdjuntos")
     */
    private $idItem;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $path;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $IdUser;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="iditemadjunto")
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdItem(): ?Items
    {
        return $this->idItem;
    }

    public function setIdItem(?Items $idItem): self
    {
        $this->idItem = $idItem;

        return $this;
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
