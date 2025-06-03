<?php

namespace App\Entity\DocumentoDigital;

use App\Repository\DocumentoDigital\SubSubSerieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubSubSerieRepository::class)
 */
class SubSubSerie
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
    private $nombre_subsubserie;

    /**
     * @ORM\ManyToOne(targetEntity=SubSerie::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_subserie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreSubSubserie(): ?string
    {
        return $this->nombre_subsubserie;
    }

    public function setNombreSubSubserie(string $nombre_subsubserie): self
    {
        $this->nombre_subsubserie = $nombre_subsubserie;

        return $this;
    }

    public function getIdSubSerie(): ?Serie
    {
        return $this->id_subserie;
    }

    public function setIdSubSerie(?Serie $id_subserie): self
    {
        $this->id_subserie = $id_subserie;

        return $this;
    }
}
