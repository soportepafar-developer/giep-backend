<?php

namespace App\Entity\DocumentoDigital;


use App\Repository\DocumentoDigital\TamanoArchivoPermitidodRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TamanoArchivoPermitidodRepository::class)
 */
class TamanoArchivoPermitidod
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ArchivosExtesionesd::class, inversedBy="id_ext_tamano_permitido")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_tipo_archivo_ext;

    /**
     * @ORM\Column(type="integer")
     */
    private $tamano;

    /**
     * @ORM\ManyToOne(targetEntity=TipoArchivod::class, inversedBy="id_tipo_archivo_tamano")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_status_tipo_archivo;

    /**
     * @ORM\Column(type="integer")
     */
    private $sw_cont_version;

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



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdTipoArchivoExt(): ?ArchivosExtesionesd
    {
        return $this->id_tipo_archivo_ext;
    }

    public function setIdTipoArchivoExt(?ArchivosExtesionesd $id_tipo_archivo_ext): self
    {
        $this->id_tipo_archivo_ext = $id_tipo_archivo_ext;

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

    public function getIdStatusTipoArchivo(): ?TipoArchivod
    {
        return $this->id_status_tipo_archivo;
    }

    public function setIdStatusTipoArchivo(?TipoArchivod $id_status_tipo_archivo): self
    {
        $this->id_status_tipo_archivo = $id_status_tipo_archivo;

        return $this;
    }

    public function getSwContVersion(): ?int
    {
        return $this->sw_cont_version;
    }

    public function setSwContVersion(int $sw_cont_version): self
    {
        $this->sw_cont_version = $sw_cont_version;

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


}
