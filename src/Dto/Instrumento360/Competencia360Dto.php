<?php
namespace App\Dto\Instrumento360;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class Competencia360Dto
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
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $createAt;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public $descripcion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $tipo;

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

    /**
     * @ORM\Column(type="string", length=255)
    */
    public $empresa;

    /**
     * @ORM\Column(type="integer")
     */
    public $escalaPonderacion;

    /**
    * @OA\Property(
    *      type="array",
    *      @OA\Items(
    *          type="array",
    *          @OA\Items()
    *      ),
    *      description="opcionesMenus"
    * )     */

    public $escalas;


    public function getEscalaPonderacion(): ?int
    {
        return $this->escalaPonderacion;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
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


    public function getEmpresa(): ?string
    {
        return $this->empresa;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    
}
