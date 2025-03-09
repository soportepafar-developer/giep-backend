<?php

namespace App\Dto\DocumentoDigital;

use App\Repository\DocumentoDigital\EstructuraOrganizativaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EstructuraOrganizativaRepository::class)
 */
class EstructuraNivelUnidadOutPutDto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

     /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $padre_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $estructura_organizativa;

    /**
     * @ORM\Column(type="string", length=10)
     */
    public $jerarquia;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPadreId(): ?int
    {
        return $this->padre_id;
    }

    public function getEstructuraOrganizativa(): ?string
    {
        return $this->estructura_organizativa;
    }

    public function getJerarquia(): ?string
    {
        return $this->jerarquia;
    }

}
