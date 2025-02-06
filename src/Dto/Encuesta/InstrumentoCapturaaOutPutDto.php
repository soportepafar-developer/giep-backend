<?php

namespace App\Dto\Encuesta;

use App\Entity\Rol;
use App\Entity\User;
use App\Repository\Encuesta\InstrumentoCapturaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;

class InstrumentoCapturaaOutPutDto
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $descripcion;

   /**
    * @OA\Property(
    *      type="array",
    *      @OA\Items(
    *          type="array",
    *          @OA\Items()
    *      ),
    *      description="idTipoUnidad"
    * )     */
    public $idTipoUnidad;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $unidad;

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
    public $path;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $fechaPublicacion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $fechaVigencia;


    /**
     * @ORM\Column(type="integer")
     */
    public $editable;
    

   /**
    * @OA\Property(
    *      type="array",
    *      @OA\Items(
    *          type="array",
    *          @OA\Items()
    *      ),
    *      description="roles"
    * )     */
    public $roles;

/**
    * @OA\Property(
    *      type="array",
    *      @OA\Items(
    *          type="array",
    *          @OA\Items()
    *      ),
    *      description="users"
    * )     */
    public $users;

   /**
    * @OA\Property(
    *      type="array",
    *      @OA\Items(
    *          type="array",
    *          @OA\Items()
    *      ),
    *      description="preguntas"
    * )     */
    private $preguntas;


   /**
    * @OA\Property(
    *      type="array",
    *      @OA\Items(
    *          type="array",
    *          @OA\Items()
    *      ),
    *      description="secciones"
    * )     */
    public $secciones;


    /**
     * @ORM\Column(type="integer")
     */
    public $publicar;

    /**
     * @ORM\Column(type="integer")
     */
    public $duracion;

    /**
     * @ORM\Column(type="integer")
     */

    public $questionsByCategory;    


   /**
     * @ORM\Column(type="string", length=250)
     */
    public $statusId;

    /**
     * @ORM\Column(type="integer")
     */
    public $orden;    

    /**
     * @ORM\Column(type="integer")
     */
    public $puntosGlobales;


    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->users = new ArrayCollection();
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


    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }


    /**
     * @return Array
     */
    public function getIdTipoUnidad()
    {
        return $this->idTipoUnidad;
    }


    public function getUnidad(): ?string
    {
        return $this->unidad;
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

    public function getFechaPublicacion(): ?\DateTimeInterface
    {
        return $this->fechaPublicacion;
    }

    public function getFechaVigencia(): ?\DateTimeInterface
    {
        return $this->fechaVigencia;
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


     /**
     * @return Array
     */    public function getRoles()
    {
        return $this->roles;
    }


    public function getPath(): ?string
    {
        return $this->path;
    }


    /**
     * @return Array
     */       
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return Array
     */    
    public function getPreguntas()
    {
        return $this->preguntas;
    }

    public function getPublicar()
    {
        return $this->publicar;
    }

    public function getDuracion(): ?string
    {
        return $this->duracion;
    }
    public function getQuestionsByCategory(){
        return $this->questionsByCategory;
    }

    public function getStatusId(): ?string
    {
        return $this->statusId;
    }
    
    /**
     * @return Array
     */    
    public function getSecciones()
    {
        return $this->secciones;
    }

    
    public function getEditable()
    {
        return $this->editable;
    }

}
