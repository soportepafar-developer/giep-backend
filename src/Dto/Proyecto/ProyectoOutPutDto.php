<?php

namespace App\Dto\Proyecto;

use App\Repository\Proyecto\ProyectoRepository;
use App\Entity\User;
use App\Dto\UserOutPutDto;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
/**
 * @ORM\Entity(repositoryClass=ProyectoRepository::class)
 */
class ProyectoOutPutDto
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
    public $nombre;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    public $descripcion; 

    /**
     * @ORM\Column(type="datetime")
     */
    public $fechaInicio;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $fechaFin;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    public $empresa;

    /**
    * @OA\Property(
    *      type="string",
    *      description="userPmo"
    * )     */
    public $userPmo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $horaestimadas;

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
    *      description="recursos"
    * )     */
    public $recursos;

  /**
    * @OA\Property(
    *      type="array",
    *      @OA\Items(
    *          type="array",
    *          @OA\Items()
    *      ),
    *      description="spring"
    * )     */
    public $springs;

  /**
    * @OA\Property(
    *      type="array",
    *      @OA\Items(
    *          type="array",
    *          @OA\Items()
    *      ),
    *      description="itemComentario"
    * )     */
    public $itemComentario;
    
    /**
    * @OA\Property(
    *      type="array",
    *      @OA\Items(
    *          type="array",
    *          @OA\Items()
    *      ),
    *      description="adjuntos"
    * )     */
    public $adjuntos;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $idstatuscalendarioproyecto;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }


    public function getFechainicio(): ?\DateTimeInterface
    {
        return $this->fechainicio;
    }

 

    public function getFechafin(): ?\DateTimeInterface
    {
        return $this->fechafin;
    }

   

    public function getEmpresa(): ?string
    {
        return $this->idempresa;
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



    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }



    public function getUpdateBy(): ?string
    {
        return $this->updateBy;
    }


    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;    
        $arr=json_decode($roles,TRUE); 
        return $arr;
    }

    
    
    public function getRecursos(): array
    {
        $recursos = $this->recursos;    
        $arr=json_decode($recursos,TRUE); 
        return $arr;
    }

    public function getUserPmo(): ?User
    {
        return $this->UserPmo;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function getHoraestimadas(): ?int
    {
        return $this->horaestimadas;
    }

    public function getIdstatuscalendarioproyecto(): ?int
    {
        return $this->idstatuscalendarioproyecto;
    }

}
