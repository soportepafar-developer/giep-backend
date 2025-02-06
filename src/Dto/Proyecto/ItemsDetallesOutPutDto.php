<?php

namespace App\Dto\Proyecto;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;

class ItemsDetallesOutPutDto
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
    public function getActivities()
    {
        return $this->activities;
    }    
}
