<?php

namespace App\Dto\StaExped;

use App\Repository\StaExped\ProfesionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfesionRepository::class)
 */
class ProfesionOutPutDto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    public $descprofesion;

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
     * @ORM\Column(type="string", length=255)
     */
    public $updateBy;

    /**
     * @ORM\OneToMany(targetEntity=EstudiosAcademicos::class, mappedBy="id_profesion")
     */
    private $id_profesionestudios;

    public function __construct()
    {
        $this->id_profesionestudios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescprofesion(): ?string
    {
        return $this->descprofesion;
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
