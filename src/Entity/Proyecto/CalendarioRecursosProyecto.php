<?php

namespace App\Entity\Proyecto;

use App\Repository\Proyecto\CalendarioRecursosProyectoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CalendarioRecursosProyectoRepository::class)
 */
class CalendarioRecursosProyecto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=RecursosProyecto::class, inversedBy="idcalendariorecursos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idrecursosproyecto;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechainicio;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechafin;

    /**
     * @ORM\Column(type="integer")
     */
    private $swactivo;

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
     * @ORM\ManyToOne(targetEntity=Empresa::class)
     */
    private $idempresa;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdrecursosproyecto(): ?RecursosProyecto
    {
        return $this->idrecursosproyecto;
    }

    public function setIdrecursosproyecto(?RecursosProyecto $idrecursosproyecto): self
    {
        $this->idrecursosproyecto = $idrecursosproyecto;

        return $this;
    }

    public function getFechainicio(): ?\DateTimeInterface
    {
        return $this->fechainicio;
    }

    public function setFechainicio(\DateTimeInterface $fechainicio): self
    {
        $this->fechainicio = $fechainicio;

        return $this;
    }

    public function getFechafin(): ?\DateTimeInterface
    {
        return $this->fechafin;
    }

    public function setFechafin(\DateTimeInterface $fechafin): self
    {
        $this->fechafin = $fechafin;

        return $this;
    }

    public function getSwactivo(): ?int
    {
        return $this->swactivo;
    }

    public function setSwactivo(int $swactivo): self
    {
        $this->swactivo = $swactivo;

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
