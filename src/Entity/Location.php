<?php

namespace App\Entity;

use App\Constant\Locales;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Location
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
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\Column(type="string")
     */
    private $phone;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\Column(type="string")
     */
    private $facebook;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\Column(type="string")
     */
    private $instagram;

    /**
     * @Assert\Valid
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\LocationTranslation",
     *     mappedBy="location",
     *     orphanRemoval=true,
     *     cascade={"remove", "persist"}
     * )
     */
    private $translations;

    /**
     * @Assert\Valid
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Review",
     *     mappedBy="location",
     *     orphanRemoval=true,
     *     cascade={"remove", "persist"}
     * )
     */
    private $reviews;

    /**
     * @Assert\Valid
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\BanquetHall",
     *     mappedBy="location",
     *     orphanRemoval=true,
     *     cascade={"remove", "persist"}
     * )
     */
    private $banquetHalls;

    /**
     * @Assert\Valid
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\LocationPhoto",
     *     mappedBy="location",
     *     orphanRemoval=true,
     *     cascade={"remove", "persist"}
     * )
     */
    private $photos;

    /**
     * @Assert\Valid
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Product",
     *     mappedBy="location",
     *     orphanRemoval=true,
     *     cascade={"remove", "persist"}
     * )
     */
    private $products;

    /**
     * @Assert\Valid
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Article",
     *     mappedBy="location",
     *     orphanRemoval=true,
     *     cascade={"remove", "persist"}
     * )
     */
    private $articles;

    /**
     * @Assert\Valid
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Hotel",
     *     mappedBy="location",
     *     orphanRemoval=true,
     *     cascade={"remove", "persist"}
     * )
     */
    private $hotels;

    /**
     * @Assert\Valid
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Gallery",
     *     mappedBy="location",
     *     orphanRemoval=true,
     *     cascade={"remove", "persist"}
     * )
     */
    private $galleries;

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
        $this->products = new ArrayCollection();
        $this->hotels = new ArrayCollection();
        $this->galleries = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->banquetHalls = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->getTitle();
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

    public function getTranslationByLocale(string $locale): LocationTranslation
    {
        $predicate = function (LocationTranslation $translation) use ($locale) {
            return $translation->getLocale() == $locale;
        };

        return $this->translations->filter($predicate)->first();
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address): void
    {
        $this->address = $address;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function setTranslations(ArrayCollection $translations): void
    {
        $this->translations = $translations;
    }

    public function addTranslation(LocationTranslation $translation)
    {
        $translation->setLocation($this);
        $this->translations->add($translation);
    }

    public function removeTranslation(LocationTranslation $translation)
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

    public function addPhoto(LocationPhoto $photo)
    {
        $photo->setLocation($this);
        $this->photos->add($photo);
    }

    public function getFeaturedPhoto()
    {
        $predicate = function (LocationPhoto $photo) {
            return $photo->getFeatured() == true;
        };

        $featuredPhoto = $this->photos->filter($predicate)->first();
        return $featuredPhoto ? $featuredPhoto :  $this->photos->first();
    }

    public function removePhoto(LocationPhoto $photo)
    {
        $this->photos->removeElement($photo);
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function setProducts(ArrayCollection $products): void
    {
        $this->products = $products;
    }

    public function getHotels(): Collection
    {
        return $this->hotels;
    }

    public function setHotels(ArrayCollection $hotels): void
    {
        $this->hotels = $hotels;
    }

    public function getGalleries(): Collection
    {
        return $this->galleries;
    }

    public function setGalleries(ArrayCollection $galleries): void
    {
        $this->galleries = $galleries;
    }

    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function setReviews($reviews): void
    {
        $this->reviews = $reviews;
    }

    public function getBanquetHalls(): Collection
    {
        return $this->banquetHalls;
    }

    public function setBanquetHalls(ArrayCollection $banquetHalls): void
    {
        $this->banquetHalls = $banquetHalls;
    }

    public function getFacebook()
    {
        return $this->facebook;
    }

    public function setFacebook($facebook): void
    {
        $this->facebook = $facebook;
    }

    public function getInstagram()
    {
        return $this->instagram;
    }

    public function setInstagram($instagram): void
    {
        $this->instagram = $instagram;
    }

    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function setArticles(ArrayCollection $articles): void
    {
        $this->articles = $articles;
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