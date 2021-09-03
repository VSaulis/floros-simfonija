<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class ArticleTranslation
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="translations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @ORM\Column(type="string")
     */
    private $locale;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

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

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale): void
    {
        $this->locale = $locale;
    }

    public function getArticle()
    {
        return $this->article;
    }

    public function setArticle($article): void
    {
        $this->article = $article;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content): void
    {
        $this->content = $content;
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