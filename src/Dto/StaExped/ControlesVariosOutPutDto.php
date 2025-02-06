<?php

namespace App\Dto\StaExped;
use App\Repository\StaExped\ControlesVariosRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ControlesVariosRepository::class)
 */
class ControlesVariosOutPutDto
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
     * @ORM\Column(type="string", length=255)
     */
    private $updateBy;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdDatosPersonales(): ?int
    {
        return $this->id_datos_personales;
    }

    public function getNormasInternas(): ?string
    {
        return $this->normas_internas;
    }

    public function getInscritoIvss(): ?string
    {
        return $this->inscrito_ivss;
    }

    public function getFormaAri(): ?string
    {
        return $this->forma_ari;
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
