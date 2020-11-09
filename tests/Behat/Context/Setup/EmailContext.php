<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context\Setup;

use Behat\Behat\Hook\Call\BeforeScenario;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\MinkExtension\Context\RawMinkContext;
use FriendsOfBehat\SymfonyExtension\Driver\SymfonyDriver;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mailer\Event\MessageEvents;
use Symfony\Component\Mime\Email;
use Webmozart\Assert\Assert;

class EmailContext extends RawMinkContext
{
    /**
     * @BeforeScenario @email
     */
    public function beforeScenario(BeforeScenarioScope $scope): void
    {
        $this->getClient()->followRedirects(false);
    }

    /**
     * @AfterScenario @email
     */
    public function afterScenario(AfterScenarioScope $scope): void
    {
        $this->getClient()->followRedirects(true);
    }

    /**
     * @Then an email should be sent to :recipient
     */
    public function emailSentTo(string $recipient): void
    {
        Assert::same(1, $this->countEmails());
        $toAddresses = [];
        foreach ($this->getEmails() as $email) {
            foreach ($email->getTo() as $address) {
                $toAddresses[] = $address->getAddress();
            }
        }
        Assert::inArray($recipient, $toAddresses);
    }

    /**
     * @return Email[]|TemplatedEmail[]
     */
    private function getEmails(string $transport = null, bool $queued = false): array
    {
        return array_map(static function (MessageEvent $messageEvent) {
            return $messageEvent->getMessage();
        }, $this->filterMessageMailerEvents($transport, $queued));
    }

    private function countEmails(string $transport = null, bool $queued = false): int
    {
        return count($this->filterMessageMailerEvents($transport, $queued));
    }

    /**
     * @return MessageEvent[]
     */
    private function filterMessageMailerEvents(string $transport = null, bool $queued = false): array
    {
        return array_filter(
            $this->getMessageMailerEvents()->getEvents($transport),
            static function (MessageEvent $event) use ($queued) {
                return ($queued && $event->isQueued()) || (!$queued && !$event->isQueued());
            }
        );
    }

    private function getMessageMailerEvents(): MessageEvents
    {
        $container = $this->getClient()->getContainer()->get('test.service_container');
        Assert::notEmpty($container, 'TestContainer was not found. Make sure you are in the "test" environment.');
        Assert::true(
            $container->has('mailer.logger_message_listener'),
            'A client must have Mailer enabled to make email assertions. Did you forget to require symfony/mailer?'
        );

        return $container->get('mailer.logger_message_listener')->getEvents();
    }

    /**
     * @return AbstractBrowser|KernelBrowser
     */
    private function getClient(): KernelBrowser
    {
        return $this->getDriver()->getClient();
    }

    private function getDriver(): SymfonyDriver
    {
        $driver = $this->getSession()->getDriver();
        if ($driver instanceof SymfonyDriver) {
            return $driver;
        }

        throw new UnsupportedDriverActionException('Expected SymfonyDriver', $driver);
    }
}
