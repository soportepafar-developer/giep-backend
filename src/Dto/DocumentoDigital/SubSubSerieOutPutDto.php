<?php

namespace App\Dto\DocumentoDigital;

use App\Repository\DocumentoDigital\SubSubSerieRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\DocumentoDigital\SubSerie;

/**
 * @ORM\Entity(repositoryClass=SubSubSerieRepository::class)
 */
class SubSubSerieOutPutDto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $nombresubsubserie;

    /**
     * @ORM\ManyToOne(targetEntity=SubSerie::class)
     * @ORM\JoinColumn(nullable=false)
     */
    public $idsubserie;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    public $cod;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreSubSubserie(): ?string
    {
        return $this->nombresubsubserie;
    }

    public function getIdSubSerie(): ?SubSerie
    {
        return $this->idsubserie;
    }

    public function getCod(): ?string
    {
        return $this->cod;
    }
    
}
