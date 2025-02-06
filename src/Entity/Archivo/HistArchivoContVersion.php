<?php

namespace App\Entity\Archivo;

use App\Entity\Archivo\Archivos;
use App\Entity\Archivo\TipoAcccionRegistro;
use App\Entity\Archivo\TipoOperaciones;
use App\Entity\Archivo\TipoOrientacion;
use App\Archivo\Repository\HistArchivoContVersionRepository;
use App\Entity\Proyecto\Empresa;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistArchivoContVersionRepository::class)
 */
class HistArchivoContVersion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="iduserhistversion")
     * @ORM\JoinColumn(nullable=false)
     */
    private $iduser;

    /**
     * @ORM\Column(type="integer")
     */
    private $tamano;

    /**
     * @ORM\Column(type="string", length=1000)
     */
    private $url_alojamiento;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ancho;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $altura;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duracion;

    /**
     * @ORM\ManyToOne(targetEntity=TipoOrientacion::class, inversedBy="idorientacionhist")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_orientacion;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $tags;

    /**
     * @ORM\Column(type="datetime" , nullable=true)
     */
    private $fecha_creacion_hist;

    /**
     * @ORM\ManyToOne(targetEntity=Archivos::class, inversedBy="idarchivo_hist")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idarchivo;

    /**
    * @ORM\Column(type="string", length=50)
    */
    private $nemotecnico;

    /**
     * @ORM\ManyToOne(targetEntity=TipoOperaciones::class, inversedBy="idoperaciones_hist")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_tipo_operaciones;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $comentario;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nombre_original;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="idhistarchivocontversion")
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(?User $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getTamano(): ?int
    {
        return $this->tamano;
    }

    public function setTamano(int $tamano): self
    {
        $this->tamano = $tamano;

        return $this;
    }

    public function getUrlAlojamiento(): ?string
    {
        return $this->url_alojamiento;
    }

    public function setUrlAlojamiento(string $url_alojamiento): self
    {
        $this->url_alojamiento = $url_alojamiento;

        return $this;
    }

    public function getAncho(): ?int
    {
        return $this->ancho;
    }

    public function setAncho(?int $ancho): self
    {
        $this->ancho = $ancho;

        return $this;
    }

    public function getAltura(): ?int
    {
        return $this->altura;
    }

    public function setAltura(?int $altura): self
    {
        $this->altura = $altura;

        return $this;
    }

    public function getDuracion(): ?int
    {
        return $this->duracion;
    }

    public function setDuracion(?int $duracion): self
    {
        $this->duracion = $duracion;

        return $this;
    }

    public function getIdOrientacion(): ?TipoOrientacion
    {
        return $this->id_orientacion;
    }

    public function setIdOrientacion(?TipoOrientacion $id_orientacion): self
    {
        $this->id_orientacion = $id_orientacion;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getFechaCreacionHist(): ?\DateTimeInterface
    {
        return $this->fecha_creacion_hist;
    }

    /**
    * @ORM\PrePersist
    */
    public function setFechaCreacionHist()
    {
        $this->fecha_creacion_hist = new \DateTime();
        
    }

    /* public function setFechaCreacionHist(\DateTimeInterface $fecha_creacion_hist): self
    {
        $this->fecha_creacion_hist = $fecha_creacion_hist;

        return $this;
    } */

    public function getIdarchivo(): ?Archivos
    {
        return $this->idarchivo;
    }

    public function setIdarchivo(?Archivos $idarchivo): self
    {
        $this->idarchivo = $idarchivo;

        return $this;
    }

    public function getNemotecnico(): ?string
    {
        return $this->nemotecnico;
    }

    public function setNemotecnico(string $nemotecnico): self
    {
        $this->nemotecnico = $nemotecnico;

        return $this;
    }

    /* public function getNemotecnico(): ?int
    {
        return $this->nemotecnico;
    }

    public function setNemotecnico(int $nemotecnico): self
    {
        $this->nemotecnico = $nemotecnico;

        return $this;
    } */

    public function getIdTipoOperaciones(): ?TipoOperaciones
    {
        return $this->id_tipo_operaciones;
    }

    public function setIdTipoOperaciones(?TipoOperaciones $id_tipo_operaciones): self
    {
        $this->id_tipo_operaciones = $id_tipo_operaciones;

        return $this;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(?string $comentario): self
    {
        $this->comentario = $comentario;

        return $this;
    }

    public function getNombreOriginal(): ?string
    {
        return $this->nombre_original;
    }

    public function setNombreOriginal(string $nombre_original): self
    {
        $this->nombre_original = $nombre_original;

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
