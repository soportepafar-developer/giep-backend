<?php

namespace App\Dto\StaExped;

use App\Repository\StaExped\TipoMotivoExpedienteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoMotivoExpedienteRepository::class)
 */
class TipoMotivoExpedienteOutPutDto
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
    public $motivoexpediente;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $createBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $updateBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotivoexpediente(): ?string
    {
        return $this->motivoexpediente;
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