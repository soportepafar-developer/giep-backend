<?php

namespace App\Dto\StaExped;

use App\Repository\StaExped\VacacionesObservacionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VacacionesObservacionRepository::class)
 */
class VacacionesObservacionOutPutDto
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
    private $id_datos_personales;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private $observacion;

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

    public function getIdDatosPersonales(): ?int
    {
        return $this->id_datos_personales;
    }

    public function getObservacion(): ?string
    {
        return $this->observacion;
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
