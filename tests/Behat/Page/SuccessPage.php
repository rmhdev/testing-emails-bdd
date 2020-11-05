<?php

declare(strict_types=1);

namespace App\Tests\Behat\Page;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

class SuccessPage extends SymfonyPage
{
    public function getRouteName(): string
    {
        return 'contact_success';
    }
}
