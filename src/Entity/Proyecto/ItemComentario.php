<?php

namespace App\Entity\Proyecto;

use App\Entity\User;
use App\Repository\Proyecto\ItemComentarioRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemComentarioRepository::class)
 */
class ItemComentario
{
    /**

     * @ORM\Id

     * @ORM\GeneratedValue

     * @ORM\Column(type="integer")

     */

    private $id;



    /**

     * @ORM\Column(type="string", length=5000)

     */

    private $comentario;



    /**

     * @ORM\ManyToOne(targetEntity=Items::class, inversedBy="itemComentario")

     * @ORM\JoinColumn(nullable=false)

     */

    private $item;



    /**

     * @ORM\ManyToOne(targetEntity=User::class)

     */

    private $idUser;

    

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

     * @ORM\Column(type="string", length=255, nullable=true)

     */

    private $updateBy;


   /**
     * @ORM\ManyToOne(targetEntity=Empresa::class, inversedBy="iditemcomentario")
     */
    private $idempresa;
    

    public function getId(): ?int

    {

        return $this->id;

    }



    public function getComentario(): ?string

    {

        return $this->comentario;

    }



    public function setComentario(string $comentario): self

    {

        $this->comentario = $comentario;



        return $this;

    }



    public function getItem(): ?Items

    {

        return $this->item;

    }



    public function setItem(?Items $item): self

    {

        $this->item = $item;



        return $this;

    }



    public function getIdUser(): ?User

    {

        return $this->idUser;

    }



    public function setIdUser(?User $idUser): self

    {

        $this->idUser = $idUser;



        return $this;

    }

    

        public function getCreateAt(): ?\DateTimeInterface

    {

        return $this->createAt;

    }



    public function setCreateAt(?\DateTimeInterface $createAt): self

    {

        $this->createAt = $createAt;



        return $this;

    }



    public function getCreateBy(): ?string

    {

        return $this->createBy;

    }



    public function setCreateBy(?string $createBy): self

    {

        $this->createBy = $createBy;



        return $this;

    }



    public function getUpdateAt(): ?\DateTimeInterface

    {

        return $this->updateAt;

    }



    public function setUpdateAt(?\DateTimeInterface $updateAt): self

    {

        $this->updateAt = $updateAt;



        return $this;

    }



    public function getUpdateBy(): ?string

    {

        return $this->updateBy;

    }



    public function setUpdateBy(?string $updateBy): self

    {

        $this->updateBy = $updateBy;



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

