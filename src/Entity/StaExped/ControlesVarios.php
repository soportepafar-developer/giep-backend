<?php

namespace App\Entity\StaExped;

use App\Repository\StaExped\ControlesVariosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ControlesVariosRepository::class)
 */
class ControlesVarios
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
    private $normas_internas;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $inscrito_ivss;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $forma_ari;

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

    public function getNormasInternas(): ?string
    {
        return $this->normas_internas;
    }

    public function setNormasInternas(string $normas_internas): self
    {
        $this->normas_internas = $normas_internas;

        return $this;
    }

    public function getInscritoIvss(): ?string
    {
        return $this->inscrito_ivss;
    }

    public function setInscritoIvss(string $inscrito_ivss): self
    {
        $this->inscrito_ivss = $inscrito_ivss;

        return $this;
    }

    public function getFormaAri(): ?string
    {
        return $this->forma_ari;
    }

    public function setFormaAri(string $forma_ari): self
    {
        $this->forma_ari = $forma_ari;

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
