<?php

namespace App\Dto\CalendarioPluggin;

use App\Repository\CalendarioPluggin\CalendarEventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

class CalendarEventOutDto
{
    public $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $end;

    /**
     * @ORM\Column(type="string", length=600)
     */
    public $title;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    public $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $classNames;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $acreditacion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $ownerEvent;

   /**
    * @OA\Property(
    *      type="array",
    *      @OA\Items(
    *          type="array",
    *          @OA\Items()
    *      ),
    *      description="usuarios"
    * )
    */
    public $calendarUsers;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $createdBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $updatedBy;


    /**
     * @ORM\Column(type="int")
     */
    public $editable;


    public function __construct()
    {
        $this->calendarUsers = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }


    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }


    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }


    public function getUrl(): ?string
    {
        return $this->url;
    }


    public function getClassNames(): ?string
    {
        return $this->classNames;
    }

    /**
     * @return Collection|CalendarUser[]
     */
    public function getCalendarUsers(): Collection
    {
        return $this->calendarUsers;
    }


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }


    public function getCreatedBy(): ?string
    {
        return $this->createdBu;
    }


    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }


    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

    public function getAcreditacion(): ?string
    {
        return $this->acreditacion;
    }

    public function getEditable(): ?int
    {
        return $this->editable;
    }


}
