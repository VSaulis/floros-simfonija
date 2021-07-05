<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RestaurantRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Restaurant
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
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\RestaurantTranslation",
     *     mappedBy="restaurant",
     *     orphanRemoval=true,
     *     cascade={"remove"}
     * )
     */
    private $translations;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\RestaurantPhoto",
     *     mappedBy="restaurant",
     *     orphanRemoval=true,
     *     cascade={"remove"}
     * )
     */
    private $photos;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\MenuItem",
     *     mappedBy="restaurant",
     *     orphanRemoval=true,
     *     cascade={"remove"}
     * )
     */
    private $menuItems;

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
        $this->menuItems = new ArrayCollection();
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

    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function setPhotos(ArrayCollection $photos): void
    {
        $this->photos = $photos;
    }

    public function getMenuItems(): Collection
    {
        return $this->menuItems;
    }

    public function setMenuItems(ArrayCollection $menuItems): void
    {
        $this->menuItems = $menuItems;
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