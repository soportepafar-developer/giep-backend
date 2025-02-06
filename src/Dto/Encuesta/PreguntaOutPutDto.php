<?php

namespace App\Dto\Encuesta;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;


class PreguntaOutPutDto
{
    /**
     * @ORM\Id
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    public $pregunta;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    public $orden;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $idInput;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $class;

    /**
     * @ORM\Column(type="smallint")
     */
    public $obligatorio;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $puntos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $IdCategoria;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $idInstrumento;

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
    * @OA\Property(
    *      type="array",
    *      @OA\Items(
    *          type="array",
    *          @OA\Items()
    *      ),
    *      description="opciones"
    * )     */
    public $opciones;

    /**
    * @OA\Property(
    *      type="array",
    *      @OA\Items(
    *          type="array",
    *          @OA\Items()
    *      ),
    *      description="respuestas"
    * )     */
    public $respuestas;

    public function __construct()
    {
        $this->opciones = new ArrayCollection();
        $this->respuestas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPregunta(): ?string
    {
        return $this->pregunta;
    }

    public function getOrden(): ?int
    {
        return $this->orden;
    }

    public function getIdInput(): ?string
    {
        return $this->idInput;
    }


    public function getClass(): ?string
    {
        return $this->class;
    }

    
    public function getObligatorio(): ?int
    {
        return $this->obligatorio;
    }

    

    public function getPuntos(): ?string
    {
        return $this->puntos;
    }

    

    public function getIdCategoria(): ?string
    {
        return $this->IdCategoria;
    }


    public function getIdInstrumento(): ?string
    {
        return $this->idInstrumento;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function getCreateBy(): ?string
    {
        return $this->createBy;
    }


    public function getUpdateAt(): string
    {
        return $this->updateAt;
    }


    public function getUpdateBy(): ?string
    {
        return $this->updateBy;
    }


    public function getOpciones(): string
    {
        return $this->opciones;
    }

    public function getRespuestas(): string
    {
        return $this->respuestas;
    }

}
