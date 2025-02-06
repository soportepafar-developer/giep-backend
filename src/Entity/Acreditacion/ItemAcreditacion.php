<?php

namespace App\Entity\Acreditacion;

use App\Entity\CalendarioPluggin\CalendarUser;
use App\Entity\Proyecto\Empresa;
use App\Repository\Acreditacion\ItemAcreditacionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemAcreditacionRepository::class)
 */
class ItemAcreditacion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity=TipoItem::class, inversedBy="itemAcreditacions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $IdTipoITem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Cantidad;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Usado;

    /**
     * @ORM\ManyToOne(targetEntity=CalendarUser::class, inversedBy="itemAcreditacions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idAcreditacion;

    /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="iditemacreditacion")
     */
    private $idempresa;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getIdTipoITem(): ?Tipoitem
    {
        return $this->IdTipoITem;
    }

    public function setIdTipoITem(?Tipoitem $IdTipoITem): self
    {
        $this->IdTipoITem = $IdTipoITem;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->Cantidad;
    }

    public function setCantidad(?int $Cantidad): self
    {
        $this->Cantidad = $Cantidad;

        return $this;
    }

    public function getUsado(): ?int
    {
        return $this->Usado;
    }

    public function setUsado(?int $Usado): self
    {
        $this->Usado = $Usado;

        return $this;
    }

    public function getIdAcreditacion(): ?CalendarUser
    {
        return $this->idAcreditacion;
    }

    public function setIdAcreditacion(?CalendarUser $idAcreditacion): self
    {
        $this->idAcreditacion = $idAcreditacion;

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
