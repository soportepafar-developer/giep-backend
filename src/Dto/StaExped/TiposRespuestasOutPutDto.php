<?php

namespace App\Dto\StaExped;

use App\Repository\StaExped\TiposRespuestasRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TiposRespuestasRepository::class)
 */
class TiposRespuestasOutPutDto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    public $respuestas;

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
     * @ORM\Column(type="string", length=255)
     */
    public $updateBy;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRespuestas(): ?string
    {
        return $this->respuestas;
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

}
