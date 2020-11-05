<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context\Ui;

use App\Tests\Behat\Page\ContactPage;
use App\Tests\Behat\Page\SuccessPage;
use Behat\MinkExtension\Context\RawMinkContext;
use Webmozart\Assert\Assert;

final class ContactContext extends RawMinkContext
{
    private ContactPage $contactPage;
    private SuccessPage $successPage;

    public function __construct(ContactPage $contactPage, SuccessPage $successPage)
    {
        $this->contactPage = $contactPage;
        $this->successPage = $successPage;
    }

    /**
     * @Given I am in the contact page
     * @Given I go to the contact page
     */
    public function iAmInContactPage(): void
    {
        $this->contactPage->open();
    }

    /**
     * @When I specify my name as :name
     */
    public function iSpecifyMyName(string $name): void
    {
        $this->contactPage->specifyName($name);
    }

    /**
     * @When I specify my email as :email
     */
    public function iSpecifyMyEmail(string $email): void
    {
        $this->contactPage->specifyEmail($email);
    }

    /**
     * @When I send the contact form
     */
    public function iSendTheForm(): void
    {
        $this->contactPage->submit();
    }

    /**
     * @Then I should be notified that the contact request has been submitted successfully
     */
    public function successPageIsOpen(): void
    {
        Assert::true($this->successPage->isOpen());
    }
}
