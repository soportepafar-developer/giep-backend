<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 */
class Document
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $filename = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $originalName = null;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private ?string $fileType = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $content = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $summary = null;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private array $analysis = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $uploadedAt = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $fileSize = null;

    public function __construct()
    {
        $this->uploadedAt = new \DateTime();
    }

    // Getters y Setters...
    public function getId(): ?int { return $this->id; }
    public function getFilename(): ?string { return $this->filename; }
    public function setFilename(string $filename): self { $this->filename = $filename; return $this; }
    public function getOriginalName(): ?string { return $this->originalName; }
    public function setOriginalName(string $originalName): self { $this->originalName = $originalName; return $this; }
    public function getFileType(): ?string { return $this->fileType; }
    public function setFileType(string $fileType): self { $this->fileType = $fileType; return $this; }
    public function getContent(): ?string { return $this->content; }
    public function setContent(?string $content): self { $this->content = $content; return $this; }
    public function getSummary(): ?string { return $this->summary; }
    public function setSummary(?string $summary): self { $this->summary = $summary; return $this; }
    public function getAnalysis(): array { return $this->analysis; }
    public function setAnalysis(?array $analysis): self { $this->analysis = $analysis; return $this; }
    public function getUploadedAt(): ?\DateTimeInterface { return $this->uploadedAt; }
    public function setUploadedAt(\DateTimeInterface $uploadedAt): self { $this->uploadedAt = $uploadedAt; return $this; }
    public function getFileSize(): ?int { return $this->fileSize; }
    public function setFileSize(int $fileSize): self { $this->fileSize = $fileSize; return $this; }
    
}
