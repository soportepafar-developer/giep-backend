<?php

namespace App\Entity\StaExped;
use App\Repository\StaExped\TokenPdfRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TokenPdfRepository::class)
 */
class TokenPdf
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
    private $datosQr;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

     /**
     * @ORM\Column(type="integer" , nullable=true)
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(?int $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getDatosQr(): ?string
    {
        return $this->datosQr;
    }

    public function setDatosQr(string $datosQr): self
    {
        $this->datosQr = $datosQr;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getIdempresa(): ?int
    {
        return $this->idempresa;
    }

    public function setIdempresa(int $idempresa): self
    {
        $this->idempresa = $idempresa;

        return $this;
    }
}
