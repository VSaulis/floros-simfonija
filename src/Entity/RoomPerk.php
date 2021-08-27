<?php

namespace App\Entity;

use App\Constant\Locales;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class RoomPerk
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Room",
     *     mappedBy="perks",
     *     cascade={"remove", "persist"}
     * )
     */
    private $rooms;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\RoomPerkTranslation",
     *     mappedBy="roomPerk",
     *     orphanRemoval=true,
     *     cascade={"remove", "persist"}
     * )
     */
    private $translations;

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

    public function getTitle(string $locale = Locales::LT): string
    {
        $translation = $this->getTranslationByLocale($locale);
        return $translation ? $translation->getTitle() : "";
    }

    public function getTranslationByLocale(string $locale): RoomPerkTranslation
    {
        $predicate = function (RoomPerkTranslation $translation) use ($locale) {
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

    public function addTranslation(RoomPerkTranslation $translation)
    {
        $translation->setRoomPerk($this);
        $this->translations->add($translation);
    }

    public function removeTranslation(RoomPerkTranslation $translation)
    {
        $this->translations->removeElement($translation);
    }

    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function setRooms($rooms): void
    {
        $this->rooms = $rooms;
    }

    public function addRoom(Room $room)
    {
        if (!$this->rooms->contains($room)) $this->rooms[] = $room;
    }

    public function removeRoom(Room $room)
    {
        if ($this->rooms->contains($room)) $this->rooms->removeElement($room);
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