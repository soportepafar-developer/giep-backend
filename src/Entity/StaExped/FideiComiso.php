<?php

namespace App\Entity\StaExped;

use App\Repository\StaExped\FideiComisoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FideiComisoRepository::class)
 */
class FideiComiso
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $id_datos_personales;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $const_depos_prestac_sociales;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $recibo_anual_intereses;

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
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $updateBy;

     /**
     * @ORM\Column(type="integer" , nullable=true)
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdDatosPersonales(): ?int
    {
        return $this->id_datos_personales;
    }

    public function setIdDatosPersonales(int $id_datos_personales): self
    {
        $this->id_datos_personales = $id_datos_personales;

        return $this;
    }

    public function getConstDeposPrestacSociales(): ?string
    {
        return $this->const_depos_prestac_sociales;
    }

    public function setConstDeposPrestacSociales(string $const_depos_prestac_sociales): self
    {
        $this->const_depos_prestac_sociales = $const_depos_prestac_sociales;

        return $this;
    }

    public function getReciboAnualIntereses(): ?string
    {
        return $this->recibo_anual_intereses;
    }

    public function setReciboAnualIntereses(string $recibo_anual_intereses): self
    {
        $this->recibo_anual_intereses = $recibo_anual_intereses;

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

    public function setUpdateBy(string $updateBy): self
    {
        $this->updateBy = $updateBy;

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
