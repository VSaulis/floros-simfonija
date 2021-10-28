<?php

namespace App\Entity;

use App\Constant\Locales;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BanquetHallRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("location", "position")
 */
class BanquetHall
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\Column(type="integer")
     */
    private $peopleCount;

    /**
     * @Assert\Valid
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\BanquetHallTranslation",
     *     mappedBy="banquetHall",
     *     orphanRemoval=true,
     *     cascade={"remove", "persist"}
     * )
     */
    private $translations;

    /**
     * @Assert\Valid
     * @ORM\OrderBy({"position" = "asc"})
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\BanquetHallPhoto",
     *     mappedBy="banquetHall",
     *     orphanRemoval=true,
     *     cascade={"remove", "persist"}
     * )
     */
    private $photos;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="banquetHalls")
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->photos = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string)$this->getTitle(Locales::LT);
    }

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

    public function getTitle(string $locale = Locales::LT): string
    {
        $translation = $this->getTranslationByLocale($locale);
        return $translation ? $translation->getTitle() : "";
    }

    public function getDescription(string $locale): string
    {
        $translation = $this->getTranslationByLocale($locale);
        return $translation ? $translation->getDescription() : "";
    }

    public function getTranslationByLocale(string $locale): BanquetHallTranslation
    {
        $predicate = function (BanquetHallTranslation $translation) use ($locale) {
            return $translation->getLocale() == $locale;
        };

        return $this->translations->filter($predicate)->first();
    }

    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function setTranslations(ArrayCollection $translations): void
    {
        $this->translations = $translations;
    }

    public function addTranslation(BanquetHallTranslation $translation)
    {
        $translation->setBanquetHall($this);
        $this->translations->add($translation);
    }

    public function removeTranslation(BanquetHallTranslation $translation)
    {
        $this->translations->removeElement($translation);
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location): void
    {
        $this->location = $location;
    }

    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function setPhotos(ArrayCollection $photos): void
    {
        $this->photos = $photos;
    }

    public function addPhoto(BanquetHallPhoto $photo)
    {
        $photo->setBanquetHall($this);
        $this->photos->add($photo);
    }

    public function removePhoto(BanquetHallPhoto $photo)
    {
        $this->photos->removeElement($photo);
    }

    public function getFeaturedPhoto()
    {
        $predicate = function (BanquetHallPhoto $photo) {
            return $photo->getPosition() == 1;
        };

        $featuredPhoto = $this->photos->filter($predicate)->first();
        return $featuredPhoto ? $featuredPhoto :  $this->photos->first();
    }

    public function getPeopleCount()
    {
        return $this->peopleCount;
    }

    public function setPeopleCount($peopleCount): void
    {
        $this->peopleCount = $peopleCount;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position): void
    {
        $this->position = $position;
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