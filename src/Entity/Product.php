<?php

namespace App\Entity;

use App\Constant\Locales;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductCategory", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

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
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\ProductTranslation",
     *     mappedBy="product",
     *     orphanRemoval=true,
     *     cascade={"remove", "persist"}
     * )
     */
    private $translations;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\ProductPhoto",
     *     mappedBy="product",
     *     orphanRemoval=true,
     *     cascade={"remove", "persist"}
     * )
     */
    private $photos;

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

    public function getTranslationByLocale(string $locale): ProductTranslation
    {
        $predicate = function (ProductTranslation $translation) use ($locale) {
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

    public function addTranslation(ProductTranslation $translation)
    {
        $translation->setProduct($this);
        $this->translations->add($translation);
    }

    public function removeTranslation(ProductTranslation $translation)
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

    public function addPhoto(ProductPhoto $photo)
    {
        $photo->setProduct($this);
        $this->photos->add($photo);
    }

    public function removePhoto(ProductPhoto $photo)
    {
        $this->photos->removeElement($photo);
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

    public function getUpdated()
    {
        return $this->updated;
    }

    public function getCreated()
    {
        return $this->created;
    }
}