<?php

namespace App\Entity\Instrumento360;

use App\Entity\Cargo;
use App\Entity\EstructuraOrganizativa;
use App\Entity\Proyecto\Empresa;
use App\Entity\User;
use App\Repository\Instrumento360\Instrumento360UsuariosAsignadosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=Instrumento360UsuariosAsignadosRepository::class)
 */
class Instrumento360UsuariosAsignados
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=EstructuraOrganizativa::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $unidadUser;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $userEvaluador;

    /**
     * @ORM\ManyToOne(targetEntity=EstructuraOrganizativa::class)
     */
    private $unidadEvaluador;

    /**
     * @ORM\ManyToOne(targetEntity=Cargo::class)
     */
    private $cargoUser;

    /**
     * @ORM\ManyToOne(targetEntity=Cargo::class)
     */
    private $cargoEvaluador;

    /**
     * @ORM\ManyToOne(targetEntity=Instrumento360::class, inversedBy="instrumento360UsuariosAsignados")
     * @ORM\JoinColumn(nullable=false)
     */
    private $instrumento360;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="string", length=255)
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

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $empresa;

   
    
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUnidadUser(): ?EstructuraOrganizativa
    {
        return $this->unidadUser;
    }

    public function setUnidadUser(?EstructuraOrganizativa $unidadUser): self
    {
        $this->unidadUser = $unidadUser;

        return $this;
    }

    public function getUserEvaluador(): ?User
    {
        return $this->userEvaluador;
    }

    public function setUserEvaluador(?User $userEvaluador): self
    {
        $this->userEvaluador = $userEvaluador;

        return $this;
    }

    public function getUnidadEvaluador(): ?EstructuraOrganizativa
    {
        return $this->unidadEvaluador;
    }

    public function setUnidadEvaluador(?EstructuraOrganizativa $unidadEvaluador): self
    {
        $this->unidadEvaluador = $unidadEvaluador;

        return $this;
    }

    public function getCargoUser(): ?Cargo
    {
        return $this->cargoUser;
    }

    public function setCargoUser(?Cargo $cargoUser): self
    {
        $this->cargoUser = $cargoUser;

        return $this;
    }

    public function getCargoEvaluador(): ?Cargo
    {
        return $this->cargoEvaluador;
    }

    public function setCargoEvaluador(?Cargo $cargoEvaluador): self
    {
        $this->cargoEvaluador = $cargoEvaluador;

        return $this;
    }

    public function getInstrumento360(): ?Instrumento360
    {
        return $this->instrumento360;
    }

    public function setInstrumento360(?Instrumento360 $instrumento360): self
    {
        $this->instrumento360 = $instrumento360;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getCreateBy(): ?string
    {
        return $this->createBy;
    }

    public function setCreateBy(string $createBy): self
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

    public function getEmpresa(): ?Empresa
    {
        return $this->empresa;
    }

    public function setEmpresa(?Empresa $empresa): self
    {
        $this->empresa = $empresa;

        return $this;
    }


}
