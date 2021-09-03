<?php

namespace App\Entity;

use App\Constant\Locales;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\Column(type="boolean")
     */
    private $visible;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\Column(type="date")
     */
    private $dateFrom;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\Column(type="date")
     */
    private $dateTo;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\ManyToOne(targetEntity="App\Entity\Location", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\ArticleTranslation",
     *     mappedBy="article",
     *     orphanRemoval=true,
     *     cascade={"remove", "persist"}
     * )
     */
    private $translations;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\ArticlePhoto",
     *     mappedBy="article",
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
        $this->visible = true;
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

    public function getContent(string $locale): string
    {
        $translation = $this->getTranslationByLocale($locale);
        return $translation ? $translation->getContent() : "";
    }

    public function getTranslationByLocale(string $locale): ArticleTranslation
    {
        $predicate = function (ArticleTranslation $translation) use ($locale) {
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

    public function addTranslation(ArticleTranslation $translation)
    {
        $translation->setArticle($this);
        $this->translations->add($translation);
    }

    public function removeTranslation(ArticleTranslation $translation)
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

    public function addPhoto(ArticlePhoto $photo)
    {
        $photo->setArticle($this);
        $this->photos->add($photo);
    }

    public function removePhoto(ArticlePhoto $photo)
    {
        $this->photos->removeElement($photo);
    }

    public function getFeaturedPhoto()
    {
        $predicate = function (ArticlePhoto $photo) {
            return $photo->getFeatured() == true;
        };

        $featuredPhoto = $this->photos->filter($predicate)->first();
        return $featuredPhoto ? $featuredPhoto :  $this->photos->first();
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location): void
    {
        $this->location = $location;
    }

    public function getDateTo()
    {
        return $this->dateTo;
    }

    public function setDateTo($dateTo): void
    {
        $this->dateTo = $dateTo;
    }

    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    public function setDateFrom($dateFrom): void
    {
        $this->dateFrom = $dateFrom;
    }

    public function getVisible()
    {
        return $this->visible;
    }

    public function setVisible($visible): void
    {
        $this->visible = $visible;
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