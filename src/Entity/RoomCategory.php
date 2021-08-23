<?php

namespace App\Entity;

use App\Constant\Locales;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoomCategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class RoomCategory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\RoomCategoryTranslation",
     *     mappedBy="category",
     *     orphanRemoval=true,
     *     cascade={"remove", "persist"}
     * )
     */
    private $translations;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Room",
     *     mappedBy="category",
     *     orphanRemoval=true,
     *     cascade={"remove"}
     * )
     */
    private $rooms;

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
        return (string) $this->getTitle(Locales::LT);
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

    public function getTitle(string $locale): string
    {
        $translation = $this->getTranslationByLocale($locale);
        return $translation ? $translation->getTitle() : "";
    }

    public function getTranslationByLocale(string $locale): RoomCategoryTranslation
    {
        $predicate = function (RoomCategoryTranslation $translation) use ($locale) {
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

    public function addTranslation(RoomCategoryTranslation $translation)
    {
        $translation->setCategory($this);
        $this->translations->add($translation);
    }

    public function removeTranslation(RoomCategoryTranslation $translation)
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

    public function getUpdated()
    {
        return $this->updated;
    }

    public function getCreated()
    {
        return $this->created;
    }
}