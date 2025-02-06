<?php

namespace App\Dto\Acreditacion;

use Doctrine\ORM\Mapping as ORM;

class TipoItemOutputDto
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $descripcion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $updateBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;


    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }


    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }


    public function getUpdateBy(): ?string
    {
        return $this->updateBy;
    }


    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }




}
