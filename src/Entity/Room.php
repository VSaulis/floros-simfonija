<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoomRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Room
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\Column(type="decimal", precision=10, scale=7)
     */
    private $longitude;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\Column(type="decimal", precision=10, scale=7)
     */
    private $latitude;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank(message="field_is_required")
     * @Assert\Regex(
     *     pattern="/^-?[0-9]+(?:\.[0-9]{0,2})?$/",
     *     message="field_is_invalid"
     * )
     */
    private $price;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="field_is_required")
     * @Assert\Url(message="field_is_invalid")
     */
    private $orderUrl;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\RoomTranslation",
     *     mappedBy="room",
     *     orphanRemoval=true,
     *     cascade={"remove", "persist"}
     * )
     */
    private $translations;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\RoomPhoto",
     *     mappedBy="room",
     *     orphanRemoval=true,
     *     cascade={"remove", "persist"}
     * )
     */
    private $photos;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\RoomPerk",
     *     inversedBy="rooms",
     *     cascade={"remove", "persist"}
     * )
     */
    private $perks;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="rooms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\ManyToOne(targetEntity="App\Entity\RoomCategory", inversedBy="rooms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="integer")
     */
    private $peopleCount;

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
        $this->perks = new ArrayCollection();
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

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): void
    {
        $this->price = $price;
    }

    public function getOrderUrl()
    {
        return $this->orderUrl;
    }

    public function setOrderUrl($orderUrl): void
    {
        $this->orderUrl = $orderUrl;
    }

    public function getTitle(string $locale): string
    {
        $translation = $this->getTranslationByLocale($locale);
        return $translation ? $translation->getTitle() : "";
    }

    public function getDescription(string $locale): string
    {
        $translation = $this->getTranslationByLocale($locale);
        return $translation ? $translation->getDescription() : "";
    }

    public function getAddress(string $locale): string
    {
        $translation = $this->getTranslationByLocale($locale);
        return $translation ? $translation->getAddress() : "";
    }

    public function getTranslationByLocale(string $locale): RoomTranslation
    {
        $predicate = function (RoomTranslation $translation) use ($locale) {
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

    public function addTranslation(RoomTranslation $translation)
    {
        $translation->setRoom($this);
        $this->translations->add($translation);
    }

    public function removeTranslation(RoomTranslation $translation)
    {
        $this->translations->removeElement($translation);
    }

    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function setPhotos(ArrayCollection $photos): void
    {
        $this->photos = $photos;
    }

    public function addPhoto(RoomPhoto $photo)
    {
        $photo->setRoom($this);
        $this->photos->add($photo);
    }

    public function removePhoto(RoomPhoto $photo)
    {
        $this->photos->removeElement($photo);
    }

    public function getFeaturedPhoto()
    {
        $predicate = function (RoomPhoto $photo) {
            return $photo->getFeatured() == true;
        };

        $featuredPhoto = $this->photos->filter($predicate)->first();
        return $featuredPhoto ? $featuredPhoto :  $this->photos->first();
    }

    public function getPerks(): Collection
    {
        return $this->perks;
    }

    public function setPerks($perks): void
    {
        $this->perks = $perks;
    }

    public function addPerk(RoomPerk $perk)
    {
        if (!$this->perks->contains($perk)) {
            $perk->addRoom($this);
            $this->perks[] = $perk;
        }
    }

    public function removePerk(RoomPerk $perk)
    {
        if ($this->perks->contains($perk)) {
            $perk->removeRoom($this);
            $this->perks->removeElement($perk);
        }
    }

    public function getPeopleCount()
    {
        return $this->peopleCount;
    }

    public function setPeopleCount($peopleCount): void
    {
        $this->peopleCount = $peopleCount;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location): void
    {
        $this->location = $location;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category): void
    {
        $this->category = $category;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function setLongitude($longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLatitude($latitude): void
    {
        $this->latitude = $latitude;
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