<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context\Setup;

use Behat\Behat\Hook\Call\BeforeScenario;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Mink\Driver\BrowserKitDriver;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;
use Symfony\Component\Mailer\DataCollector\MessageDataCollector;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Webmozart\Assert\Assert;

class EmailContext extends RawMinkContext
{
    /**
     * @BeforeScenario @email
     */
    public function beforeScenario(BeforeScenarioScope $scope): void
    {
        // Trying to enable the profiler here does not work...
//        $this->getKernelBrowser()->followRedirects(false);
//        $this->getKernelBrowser()->enableProfiler();
    }

    /**
     * @AfterScenario @email
     */
    public function afterScenario(AfterScenarioScope $scope)
    {
        $this->getKernelBrowser()->followRedirects(true);
    }

    /**
     * @When I do not follow redirects
     */
    public function iDoNotFollowRedirects(): void
    {
        $this->getKernelBrowser()->followRedirects(false);
        $this->getKernelBrowser()->enableProfiler();
    }

    /**
     * @Then an email should be sent to :recipient
     */
    public function emailSentTo(string $recipient): void
    {
        $email = $this->getCollectedEmail();
        $toAddresses = array_map(static function (Address $address) {
            return $address->getAddress();
        }, $email->getTo());
        Assert::inArray($recipient, $toAddresses);
    }

    private function getCollectedEmail(): Email
    {
        $messages = $this->getMessageDataCollector()->getEvents()->getMessages();
        Assert::count($messages, 1);

        return $messages[0];
    }

    /**
     * @return DataCollectorInterface|MessageDataCollector
     */
    private function getMessageDataCollector(): MessageDataCollector
    {
        /** @see https://symfony.com/doc/5.1/email.html#problem-the-collector-doesn-t-contain-the-email */
        $browser = $this->getKernelBrowser();
        Assert::false($browser->isFollowingRedirects(), 'You should disable redirects');
        $browser->enableProfiler();

        /** @see https://symfony.com/doc/5.1/email.html#problem-the-collector-object-is-null */
        $profile = $browser->getProfile();
        Assert::notEmpty($profile, 'The profiler is not enabled');
        Assert::true($profile->hasCollector('mailer'), 'The profiler does not have a "mailer" collector');

        return $profile->getCollector('mailer');
    }

    /**
     * @return AbstractBrowser|KernelBrowser
     */
    private function getKernelBrowser(): KernelBrowser
    {
        $driver = $this->getSession()->getDriver();
        if ($driver instanceof BrowserKitDriver) {
            return $driver->getClient();
        }

        throw new UnsupportedDriverActionException('Expected BrowserKitDriver', $driver);
    }
}
