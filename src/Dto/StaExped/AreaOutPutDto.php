<?php

namespace App\Dto\StaExped;

use App\Repository\StaExped\AreaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AreaRepository::class)
 */
class AreaOutPutDto
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
    private $descarea;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $iddepartamentos;

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
     * @ORM\Column(type="string", length=255)
     */
    private $updateBy;

    

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescarea(): ?string
    {
        return $this->descarea;
    }

    public function getIddepartamentos(): ?int
    {
        return $this->iddepartamentos;
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
