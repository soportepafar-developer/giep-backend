<?php

namespace App\Entity\Proyecto;

use App\Entity\User;
use App\Repository\Proyecto\ItemsHorasTrabajadasRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemsHorasTrabajadasRepository::class)
 */
class ItemsHorasTrabajadas
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
    private $pesotrabajado;

    /**
     * @ORM\Column(type="integer")
     */
    private $remanente;

    /**
     * @ORM\Column(type="integer")
     */
    private $idspring;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalRemaningMax;

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

    /**
     * @ORM\Column(type="integer")
     */
    private $esperadorestante;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="iditemshorastrabajadas")
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPesotrabajado(): ?int
    {
        return $this->pesotrabajado;
    }

    public function setPesotrabajado(int $pesotrabajado): self
    {
        $this->pesotrabajado = $pesotrabajado;

        return $this;
    }

    public function getRemanente(): ?int
    {
        return $this->remanente;
    }

    public function setRemanente(int $remanente): self
    {
        $this->remanente = $remanente;

        return $this;
    }

    public function getIdspring(): ?int
    {
        return $this->idspring;
    }

    public function setIdspring(int $idspring): self
    {
        $this->idspring = $idspring;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getTotalRemaningMax(): ?int
    {
        return $this->totalRemaningMax;
    }

    public function setTotalRemaningMax(int $totalRemaningMax): self
    {
        $this->totalRemaningMax = $totalRemaningMax;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(?\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getCreateBy(): ?string
    {
        return $this->createBy;
    }

    public function setCreateBy(?string $createBy): self
    {
        $this->createBy = $createBy;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getUpdateBy(): ?string
    {
        return $this->updateBy;
    }

    public function setUpdateBy(?string $updateBy): self
    {
        $this->updateBy = $updateBy;

        return $this;
    }
    
     public function getEsperadorestante(): ?int
    {
        return $this->esperadorestante;
    }

    public function setEsperadorestante(int $esperadorestante): self
    {
        $this->esperadorestante = $esperadorestante;

        return $this;
    }

    public function getIdempresa(): ?Empresa
    {
        return $this->idempresa;
    }

    public function setIdempresa(?Empresa $idempresa): self
    {
        $this->idempresa = $idempresa;

        return $this;
    }
    
}