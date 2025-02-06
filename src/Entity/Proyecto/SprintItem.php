<?php

namespace App\Entity\Proyecto;

use App\Repository\Proyecto\SprintItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SprintItemRepository::class)
 */
class SprintItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Spring::class, inversedBy="sprintItems")
     */
    private $IdSpring;

    /**
     * @ORM\ManyToOne(targetEntity=Items::class, inversedBy="sprintItems")
     */
    private $IdItem;

     /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idsprititem")
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdSpring(): ?Spring
    {
        return $this->IdSpring;
    }

    public function setIdSpring(?Spring $IdSpring): self
    {
        $this->IdSpring = $IdSpring;

        return $this;
    }

    public function getIdItem(): ?Items
    {
        return $this->IdItem;
    }

    public function setIdItem(?Items $IdItem): self
    {
        $this->IdItem = $IdItem;

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
