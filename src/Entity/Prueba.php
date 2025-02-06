<?php

namespace App\Entity;

use App\Repository\PruebaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PruebaRepository::class)
 */
class Prueba
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $pd = [];

    /**
     * @ORM\Column(type="object", nullable=true)
     */
    private $prd;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dd;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPd(): ?array
    {
        return $this->pd;
    }

    public function setPd(?array $pd): self
    {
        $this->pd = $pd;

        return $this;
    }

    public function getPrd()
    {
        return $this->prd;
    }

    public function setPrd($prd): self
    {
        $this->prd = $prd;

        return $this;
    }

    public function getDd(): ?string
    {
        return $this->dd;
    }

    public function setDd(string $dd): self
    {
        $this->dd = $dd;

        return $this;
    }
}
