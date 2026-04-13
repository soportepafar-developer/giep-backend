<?php

namespace App\Dto\DocumentoDigital;

use App\Repository\DocumentoDigital\TipoStatusArchivodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoStatusArchivodRepository::class)
 */
class TipoStatusArchivoOutPutDto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    public $nombrestatus;

     /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $createAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $createBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $updateAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $updateBy;

    /**
     * @ORM\Column(type="integer" , nullable=true)
     */
    public $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreStatus(): ?string
    {
        return $this->nombrestatus;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function getCreateBy(): ?string
    {
        return $this->createBy;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function getUpdateBy(): ?string
    {
        return $this->updateBy;
    }

    public function getIdempresa(): ?int
    {
        return $this->idempresa;
    }
}
