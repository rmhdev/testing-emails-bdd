<?php

declare(strict_types=1);

namespace App\Tests\Behat\Page;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Webmozart\Assert\Assert;

class ContactPage extends SymfonyPage
{
    public function getRouteName(): string
    {
        return 'contact';
    }

    public function specifyName(string $name): void
    {
        $this->getDocument()->fillField('Name', $name);
    }

    public function specifyEmail(string $email): void
    {
        $this->getDocument()->fillField('Email', $email);
    }

    public function submit(): void
    {
        $button = $this->getDocument()->find('css', 'button[type=submit]');
        Assert::notNull($button, 'Contact page: submit button not found');
        $button->press();
    }
}
