<?php
namespace App\Dto\StaExped;
use App\Repository\StaExped\EstudiosAcademicosRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=EstudiosAcademicosRepository::class)
 */
class EstudiosAcademicosOutPutDto
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
    private $iddatos_personales;

    /**
     * @ORM\Column(type="integer")
     */
    private $idprofesion;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_graduado;

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

    public function getIdDatosPersonales(): ?string
    {
        return $this->id_datos_personales;
    }

    
    public function getIdProfesion(): ?int
    {
        return $this->idprofesion;
    }

    
    public function getFechaGraduado(): ?\DateTimeInterface
    {
        return $this->fecha_graduado;
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
