<?php

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ContactDTO
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="1", max="255")
     */
    public string $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public string $email;
}
