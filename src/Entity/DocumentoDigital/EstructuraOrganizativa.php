<?php

namespace App\Entity\DocumentoDigital;

use App\Repository\DocumentoDigital\EstructuraOrganizativaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EstructuraOrganizativaRepository::class)
 */
class EstructuraOrganizativa
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $padre_id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nivel_unidad;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $estructura_organizativa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPadreId(): ?int
    {
        return $this->padre_id;
    }

    public function setPadreId(?int $padre_id): self
    {
        $this->padre_id = $padre_id;

        return $this;
    }

    public function getNivelUnidad(): ?string
    {
        return $this->nivel_unidad;
    }

    public function setNivelUnidad(string $nivel_unidad): self
    {
        $this->nivel_unidad = $nivel_unidad;

        return $this;
    }

    public function getEstructuraOrganizativa(): ?string
    {
        return $this->estructura_organizativa;
    }

    public function setEstructuraOrganizativa(string $estructura_organizativa): self
    {
        $this->estructura_organizativa = $estructura_organizativa;

        return $this;
    }
}
