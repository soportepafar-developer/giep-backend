<?php

namespace App\Entity\Proyecto;

use App\Entity\Calendario\CalendarioProyecto;
use App\Entity\Calendario\Statuscalendarioproyecto;
use App\Entity\Rol;
use App\Entity\User;
use App\Repository\Proyecto\ProyectoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProyectoRepository::class)
 */
class Proyecto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $descripcion; 

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechainicio;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechafin;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $idempresa;

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $updateBy;

    /**
     * @ORM\ManyToMany(targetEntity=Rol::class)
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity=Recurso::class, mappedBy="proyectoId", orphanRemoval=true)
     */
    private $recursos;

    /**
     * @ORM\OneToMany(targetEntity=Spring::class, mappedBy="idproyecto")
     */
    private $springs;

    /**
     * @ORM\OneToMany(targetEntity=ProyectoAdjunto::class, mappedBy="idProyecto", orphanRemoval=true)
     */
    private $proyectoAdjuntos;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $IdUserPmo;

     /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $horaestimadas;

    /**
     * @ORM\OneToMany(targetEntity=CalendarioProyecto::class, mappedBy="id_proyecto")
     */
    private $idcalendarioproyecto;

    /**
     * @ORM\ManyToOne(targetEntity=Statuscalendarioproyecto::class, inversedBy="idstatuscalendario")
     */
    private $idstatuscalendarioproyecto;

   

    

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->recursos = new ArrayCollection();
        $this->springs = new ArrayCollection();
        $this->proyectoAdjuntos = new ArrayCollection();
        $this->idrecursosproyecto = new ArrayCollection();
        $this->idproyectoid = new ArrayCollection();
        $this->idcalendarioproyecto = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

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

    public function setFechafin(?\DateTimeInterface $fechafin): self
    {
        $this->fechafin = $fechafin;

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

    public function setUpdateBy(?string $updateBy): self
    {
        $this->updateBy = $updateBy;

        return $this;
    }

    /**
     * @return Collection|Rol[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Rol $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(Rol $role): self
    {
        $this->roles->removeElement($role);

        return $this;
    }

    /**
     * @return Collection|Recurso[]
     */
    public function getRecursos(): Collection
    {
        return $this->recursos;
    }

    public function addRecursos(Recurso $recursos): self
    {
        if (!$this->recursos->contains($recursos)) {
            $this->recursos[] = $recursos;
            $recursos->setProyectoId($this);
        }

        return $this;
    }

    public function removeRecursos(Recurso $recursos): self
    {
        if ($this->yes->removeElement($recursos)) {
            // set the owning side to null (unless already changed)
            if ($recursos->getProyectoId() === $this) {
                $recursos->setProyectoId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Spring[]
     */
    public function getSprings(): Collection
    {
        return $this->springs;
    }

    public function addSpring(Spring $spring): self
    {
        if (!$this->springs->contains($spring)) {
            $this->springs[] = $spring;
            $spring->setIdproyecto($this);
        }

        return $this;
    }

    public function removeSpring(Spring $spring): self
    {
        if ($this->springs->removeElement($spring)) {
            // set the owning side to null (unless already changed)
            if ($spring->getIdproyecto() === $this) {
                $spring->setIdproyecto(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProyectoAdjunto[]
     */
    public function getProyectoAdjuntos(): Collection
    {
        return $this->proyectoAdjuntos;
    }

    public function addProyectoAdjunto(ProyectoAdjunto $proyectoAdjunto): self
    {
        if (!$this->proyectoAdjuntos->contains($proyectoAdjunto)) {
            $this->proyectoAdjuntos[] = $proyectoAdjunto;
            $proyectoAdjunto->setIdProyecto($this);
        }

        return $this;
    }

    public function removeProyectoAdjunto(ProyectoAdjunto $proyectoAdjunto): self
    {
        if ($this->proyectoAdjuntos->removeElement($proyectoAdjunto)) {
            // set the owning side to null (unless already changed)
            if ($proyectoAdjunto->getIdProyecto() === $this) {
                $proyectoAdjunto->setIdProyecto(null);
            }
        }

        return $this;
    }

    public function getIdUserPmo(): ?User
    {
        return $this->IdUserPmo;
    }

    public function setIdUserPmo(?User $IdUserPmo): self
    {
        $this->IdUserPmo = $IdUserPmo;

        return $this;
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

    public function getHoraestimadas(): ?int
    {
        return $this->horaestimadas;
    }

    public function setHoraestimadas(?int $horaestimadas): self
    {
        $this->horaestimadas = $horaestimadas;

        return $this;
    }

    public function addRecurso(Recurso $recurso): self
    {
        if (!$this->recursos->contains($recurso)) {
            $this->recursos[] = $recurso;
            $recurso->setProyectoId($this);
        }

        return $this;
    }

    public function removeRecurso(Recurso $recurso): self
    {
        if ($this->recursos->removeElement($recurso)) {
            // set the owning side to null (unless already changed)
            if ($recurso->getProyectoId() === $this) {
                $recurso->setProyectoId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CalendarioProyecto[]
     */
    public function getIdcalendarioproyecto(): Collection
    {
        return $this->idcalendarioproyecto;
    }

    public function addIdcalendarioproyecto(CalendarioProyecto $idcalendarioproyecto): self
    {
        if (!$this->idcalendarioproyecto->contains($idcalendarioproyecto)) {
            $this->idcalendarioproyecto[] = $idcalendarioproyecto;
            $idcalendarioproyecto->setIdProyecto($this);
        }

        return $this;
    }

    public function removeIdcalendarioproyecto(CalendarioProyecto $idcalendarioproyecto): self
    {
        if ($this->idcalendarioproyecto->removeElement($idcalendarioproyecto)) {
            // set the owning side to null (unless already changed)
            if ($idcalendarioproyecto->getIdProyecto() === $this) {
                $idcalendarioproyecto->setIdProyecto(null);
            }
        }

        return $this;
    }

    public function getIdstatuscalendarioproyecto(): ?Statuscalendarioproyecto
    {
        return $this->idstatuscalendarioproyecto;
    }

    public function setIdstatuscalendarioproyecto(?Statuscalendarioproyecto $idstatuscalendarioproyecto): self
    {
        $this->idstatuscalendarioproyecto = $idstatuscalendarioproyecto;

        return $this;
    }

    



}
