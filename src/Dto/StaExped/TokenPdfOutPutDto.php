<?php

namespace App\Dto\StaExped;

//use App\Entity\User;
use App\Repository\StaExped\TokenPdfRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TokenPdfRepository::class)
 */
class TokenPdfOutPutDto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $idUser;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $datosQr;

     /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $solicitud;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function getDatosQr(): ?string
    {
        return $this->datosQr;
    }

    public function getHashtag(): ?string
    {
        return $this->solicitud;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

}
