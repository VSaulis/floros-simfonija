<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MenuItemCategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class MenuItemCategory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\MenuItemCategoryTranslation",
     *     mappedBy="category",
     *     orphanRemoval=true,
     *     cascade={"remove"}
     * )
     */
    private $translations;

    /**
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\MenuItem",
     *     mappedBy="category",
     *     orphanRemoval=true,
     *     cascade={"remove"}
     * )
     */
    private $menuItems;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MenuItemCategory", inversedBy="children")
     * @ORM\JoinColumn(nullable=true)
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MenuItemCategory", mappedBy="parent", orphanRemoval=true, fetch="EAGER")
     */
    private $children;

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
        $this->menuItems = new ArrayCollection();
        $this->children = new ArrayCollection();
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

    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function setTranslations(ArrayCollection $translations): void
    {
        $this->translations = $translations;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function setChildren(ArrayCollection $children): void
    {
        $this->children = $children;
    }

    public function getMenuItems(): Collection
    {
        return $this->menuItems;
    }

    public function setMenuItems(ArrayCollection $menuItems): void
    {
        $this->menuItems = $menuItems;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent): void
    {
        $this->parent = $parent;
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