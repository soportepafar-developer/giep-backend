<?php

namespace App\Dto\StaExped;
use App\Repository\StaExped\EspecialidadesAreasRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EspecialidadesAreasRepository::class)
 */
class EspecialidadesAreasOutPutDto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_personales;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_area;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPersonales(): ?int
    {
        return $this->id_personales;
    }

    public function getIdArea(): ?int
    {
        return $this->id_area;
    }
   
    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
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

}
