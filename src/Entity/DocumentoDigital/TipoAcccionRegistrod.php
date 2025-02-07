<?php

namespace App\Entity\DocumentoDigital;

use App\Entity\DocumentoDigital\HistArchivoContVersiond;

use App\Repository\DocumentoDigital\TipoAcccionRegistrodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoAcccionRegistrodRepository::class)
 */
class TipoAcccionRegistrod
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $accion_registro;

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
     * @ORM\OneToMany(targetEntity=Archivosd::class, mappedBy="id_accion_registro")
     */
    private $idaccion_registro_archivo;

    /**
     * @ORM\OneToMany(targetEntity=HistArchivoContVersiond::class, mappedBy="idaccionregistro")
     */
    private $idaccionregistro_hist;


    public function __construct()
    {
        $this->idaccion_registro_archivo = new ArrayCollection();
        $this->idaccionregistro_hist = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccionRegistro(): ?string
    {
        return $this->accion_registro;
    }

    public function setAccionRegistro(string $accion_registro): self
    {
        $this->accion_registro = $accion_registro;

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
     * @return Collection|HistArchivoContVersiond[]
     */
    public function getIdaccionregistroHist(): Collection
    {
        return $this->idaccionregistro_hist;
    }

    public function addIdaccionregistroHist(HistArchivoContVersiond $idaccionregistroHist): self
    {
        if (!$this->idaccionregistro_hist->contains($idaccionregistroHist)) {
            $this->idaccionregistro_hist[] = $idaccionregistroHist;
            $idaccionregistroHist->setIdaccionregistro($this);
        }

        return $this;
    }

    public function removeIdaccionregistroHist(HistArchivoContVersiond $idaccionregistroHist): self
    {
        if ($this->idaccionregistro_hist->removeElement($idaccionregistroHist)) {
            // set the owning side to null (unless already changed)
            if ($idaccionregistroHist->getIdaccionregistro() === $this) {
                $idaccionregistroHist->setIdaccionregistro(null);
            }
        }

        return $this;
    }


}
