<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context\Ui;

use App\Tests\Behat\Page\ContactPage;
use App\Tests\Behat\Page\SuccessPage;
use Behat\MinkExtension\Context\RawMinkContext;
use FriendsOfBehat\SymfonyExtension\Driver\SymfonyDriver;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\AbstractBrowser;
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
     * @Then I should see a confirmation that the message has been sent successfully
     */
    public function successPageIsOpen(): void
    {
        Assert::true($this->successPage->isOpen());
    }

    /**
     * @return AbstractBrowser|KernelBrowser
     */
    private function getKernelBrowser(): KernelBrowser
    {
        $driver = $this->getSession()->getDriver();
        if ($driver instanceof SymfonyDriver) {
            return $driver->getClient();
        }

        throw new \UnexpectedValueException(sprintf('Driver is %s, expected SymfonyDriver', get_class($driver)));
    }
}
