<?php

namespace App\Dto\Evaluacion;

use App\Entity\Rol;
use App\Entity\User;
use App\Repository\Encuesta\InstrumentoCapturaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;

class InstrumentoCapturaUsersOutPutDto
{
    /**
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $primerNombre;

        /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $primerApellido;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $respondida;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $fecha;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $estado;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $pais;

}
