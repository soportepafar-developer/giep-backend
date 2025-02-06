<?php

namespace App\Dto\CalendarioPluggin;

use App\Repository\CalendarioPluggin\CalendarEventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

class UsersOutDto
{
    public $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $email;

    /**
     * @ORM\Column(type="string, nullable=true)
     */
    public $nombre;


    public function __construct()
    {
    }

}
