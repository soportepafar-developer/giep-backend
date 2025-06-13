<?php

namespace App\Dto\DocumentoDigital;

use App\Repository\DocumentoDigital\SubSerieRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\DocumentoDigital\Serie;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

/**
 * @ORM\Entity(repositoryClass=SubSerieRepository::class)
 */
class SubSerieOutPutDto
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
    public $nombresubserie;

    /**
     * @ORM\ManyToOne(targetEntity=Serie::class)
     * @ORM\JoinColumn(nullable=false)
     */
    public $id_serie;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    public $cod;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreSubserie(): ?string
    {
        return $this->nombresubserie;
    }

    public function getIdSerie(): ?Serie
    {
        return $this->id_serie;
    }

    public function getCod(): ?string
    {
        return $this->cod;
    }
}
