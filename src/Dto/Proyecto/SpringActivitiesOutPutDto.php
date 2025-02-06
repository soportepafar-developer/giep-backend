<?php

namespace App\Dto\Proyecto;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;

class SpringActivitiesOutPutDto
{

   /**
    * @OA\Property(
    *      type="array",
    *      @OA\Items(
    *          type="array",
    *          @OA\Items()
    *      ),
    *      description="activities"
    * )     */
    public $activities;


    /**
     * @return Array
     */
    public function getActivities()
    {
        return $this->activities;
    }    
}
