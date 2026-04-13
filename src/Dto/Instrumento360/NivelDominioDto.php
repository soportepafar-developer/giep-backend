<?php
namespace App\Dto\Instrumento360;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class NivelDominioDto
{
    /**
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $nombre;

    /**
     * @ORM\Column(type="integer")
     */
    public $valor;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public $descripcion;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $empresa;


    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }


    public function getValor(): ?int
    {
        return $this->valor;
    }


    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }


    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createdatat;
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


    public function getEmpresa(): ?string
    {
        return $this->empresa;
    }

    
}
