<?php

namespace App\Dto\Instrumento360;
use App\Repository\Evaluacion\SeccionEvaluacion360Repository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;

class SeccionEvaluacion360Dto
{
    /**
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $nombre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $orden;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $evaluacion;

    /**
    * @OA\Property(
    *      type="array",
    *      @OA\Items(
    *          type="array",
    *          @OA\Items()
    *      ),
    *      description="preguntas"
    * )     */
    public $preguntas;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $status;

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

    public function __construct()
    {
        $this->preguntas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }


    public function getOrden(): ?int
    {
        return $this->orden;
    }



    public function getEvaluacion(): ?string
    {
        return $this->evaluacion;
    }


    /**
     * @return Array
    **/
    public function getPreguntas()
    {
        return $this->preguntas;
    }


    public function getStatus(): ?string
    {
        return $this->status;
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
