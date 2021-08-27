<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class BanquetHallPhoto
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\ManyToOne(targetEntity="App\Entity\BanquetHall", inversedBy="photos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $banquetHall;

    /**
     * @ORM\Column(type="boolean")
     */
    private $featured;

    /**
     * @ORM\Column(type="string")
     */
    private $fileName;

    /**
     * @ORM\Column(type="integer")
     */
    private $fileSize;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @Vich\UploadableField(mapping="banquet_halls_photos", fileNameProperty="fileName", size="fileSize")
     */
    private $file;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created = new DateTime('now');
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated = new DateTime('now');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(File $file = null): void
    {
        $this->file = $file;
        if ($file) $this->updated = new DateTime('now');
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setFileName($fileName): void
    {
        $this->fileName = $fileName;
    }

    public function getFileSize()
    {
        return $this->fileSize;
    }

    public function setFileSize($fileSize): void
    {
        $this->fileSize = $fileSize;
    }

    public function getBanquetHall()
    {
        return $this->banquetHall;
    }

    public function setBanquetHall($banquetHall): void
    {
        $this->banquetHall = $banquetHall;
    }

    public function getFeatured()
    {
        return $this->featured;
    }

    public function setFeatured($featured): void
    {
        $this->featured = $featured;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function getCreated()
    {
        return $this->created;
    }
}