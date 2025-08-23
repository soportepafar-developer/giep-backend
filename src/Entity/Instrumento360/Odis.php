<?php

namespace App\Entity\Instrumento360;

use App\Entity\Cargo;
use App\Entity\User;
use App\Repository\Instrumento360\OdisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OdisRepository::class)
 */
class Odis
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Instrumento360::class, inversedBy="odis")
     */
    private $instrumento360;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Cargo::class)
     */
    private $UserCargo;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $userEvaluador;

    /**
     * @ORM\ManyToOne(targetEntity=Cargo::class)
     */
    private $cargoEvaluador;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $updateBy;

    /**
     * @ORM\OneToMany(targetEntity=OdisObjetivos::class, mappedBy="odi", orphanRemoval=true)
     */
    private $odisObjetivos;

    public function __construct()
    {
        $this->odisObjetivos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUserCargo(): ?Cargo
    {
        return $this->UserCargo;
    }

    public function setUserCargo(?Cargo $UserCargo): self
    {
        $this->UserCargo = $UserCargo;

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

    public function getCargoEvaluador(): ?Cargo
    {
        return $this->cargoEvaluador;
    }

    public function setCargoEvaluador(?Cargo $cargoEvaluador): self
    {
        $this->cargoEvaluador = $cargoEvaluador;

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

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?string $createdBy): self
    {
        $this->createdBy = $createdBy;

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
     * @return Collection|OdisObjetivos[]
     */
    public function getOdisObjetivos(): Collection
    {
        return $this->odisObjetivos;
    }

    public function addOdisObjetivo(OdisObjetivos $odisObjetivo): self
    {
        if (!$this->odisObjetivos->contains($odisObjetivo)) {
            $this->odisObjetivos[] = $odisObjetivo;
            $odisObjetivo->setOdi($this);
        }

        return $this;
    }

    public function removeOdisObjetivo(OdisObjetivos $odisObjetivo): self
    {
        if ($this->odisObjetivos->removeElement($odisObjetivo)) {
            // set the owning side to null (unless already changed)
            if ($odisObjetivo->getOdi() === $this) {
                $odisObjetivo->setOdi(null);
            }
        }

        return $this;
    }
}
