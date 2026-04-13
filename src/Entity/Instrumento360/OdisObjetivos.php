<?php

namespace App\Entity\Instrumento360;

use App\Repository\Instrumento360\OdisObjetivosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OdisObjetivosRepository::class)
 */
class OdisObjetivos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Odis::class, inversedBy="odisObjetivos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $odi;

    /**
     * @ORM\ManyToOne(targetEntity=RangosOdis::class, inversedBy="odisObjetivos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $odirango;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOdi(): ?Odis
    {
        return $this->odi;
    }

    public function setOdi(?Odis $odi): self
    {
        $this->odi = $odi;

        return $this;
    }

    public function getOdirango(): ?RangosOdis
    {
        return $this->odirango;
    }

    public function setOdirango(?RangosOdis $odirango): self
    {
        $this->odirango = $odirango;

        return $this;
    }
}
