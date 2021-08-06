<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Message
{
    /**
     * @Assert\NotBlank(message="field_is_required")
     */
    private $name;

    /**
     * @Assert\NotBlank(message="field_is_required")
     * @Assert\Email(message="field_is_invalid")
     */
    private $email;

    /**
     * @Assert\NotBlank(message="field_is_required")
     */
    private $phone;

    /**
     * @Assert\NotBlank(message="field_is_required")
     */
    private $subject;

    /**
     * @Assert\NotBlank(message="field_is_required")
     */
    private $message;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
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

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject): void
    {
        $this->subject = $subject;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message): void
    {
        $this->message = $message;
    }

}