<?php

namespace App\Entity;

use App\Constant\Locales;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HotelRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Hotel
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\Column(type="string")
     */
    private $address;

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
     * @ORM\OneToOne (
     *     targetEntity="App\Entity\HotelLogo",
     *     mappedBy="hotel",
     *     orphanRemoval=true,
     *     cascade={"remove", "persist"},
     * )
     */
    private $logo;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\HotelTranslation",
     *     mappedBy="hotel",
     *     orphanRemoval=true,
     *     cascade={"remove", "persist"}
     * )
     */
    private $translations;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Room",
     *     mappedBy="hotel",
     *     orphanRemoval=true,
     *     cascade={"remove"}
     * )
     */
    private $rooms;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="hotels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

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
        $this->rooms = new ArrayCollection();
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

    public function getTranslationByLocale(string $locale): HotelTranslation
    {
        $predicate = function (HotelTranslation $translation) use ($locale) {
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

    public function addTranslation(HotelTranslation $translation)
    {
        $translation->setHotel($this);
        $this->translations->add($translation);
    }

    public function removeTranslation(HotelTranslation $translation)
    {
        $this->translations->removeElement($translation);
    }

    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function setRooms(ArrayCollection $rooms): void
    {
        $this->rooms = $rooms;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location): void
    {
        $this->location = $location;
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

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address): void
    {
        $this->address = $address;
    }

    public function getMinPrice(): float
    {
        if ($this->rooms->count() == 0) return 0;
        $minPrice = $this->rooms->first()->getPrice();

        foreach ($this->rooms as $room) {
            if ($room->getPrice() < $minPrice) $minPrice = $room->getPrice();
        }

        return $minPrice;
    }

    public function getMaxPrice(): float
    {
        $maxPrice = 0;

        foreach ($this->rooms as $room) {
            if ($room->getPrice() > $maxPrice) $maxPrice = $room->getPrice();
        }

        return $maxPrice;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function setLogo(HotelLogo $logo): void
    {
        $logo->setHotel($this);
        $this->logo = $logo;
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