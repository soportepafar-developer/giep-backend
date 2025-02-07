<?php

namespace App\Entity\DocumentoDigital;

use App\Repository\DocumentoDigital\SubSerieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubSerieRepository::class)
 */
class SubSerie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre_subserie;

    /**
     * @ORM\ManyToOne(targetEntity=Serie::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_serie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreSubserie(): ?string
    {
        return $this->nombre_subserie;
    }

    public function setNombreSubserie(string $nombre_subserie): self
    {
        $this->nombre_subserie = $nombre_subserie;

        return $this;
    }

    public function getIdSerie(): ?Serie
    {
        return $this->id_serie;
    }

    public function setIdSerie(?Serie $id_serie): self
    {
        $this->id_serie = $id_serie;

        return $this;
    }
}
