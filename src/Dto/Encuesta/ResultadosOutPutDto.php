<?php

namespace App\Dto\Encuesta;

use App\Entity\Rol;
use App\Entity\User;
use App\Repository\Encuesta\InstrumentoCapturaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;

class ResultadosOutPutDto
{

   /**
    * @OA\Property(
    *      type="array",
    *      @OA\Items(
    *          type="array",
    *          @OA\Items()
    *      ),
    *      description="data"
    * )     */
    public $data;


    /**
     * @return Array
     */
    public function getData()
    {
        return $this->data;
    }    
}
