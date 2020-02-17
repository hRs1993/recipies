<?php

namespace App\EventSubscriber;

use App\Entity\Subscriber;
use App\Event\SubscriptionCreatedEvent;
use App\Services\Subscription\ActivationManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Validator\Constraints\Uuid;

class SubscriptionSubscriber implements EventSubscriberInterface
{
    /**
     * @var ActivationManager
     */
    private $activationManager;

    public function __construct(ActivationManager $activationManager)
    {
        $this->activationManager = $activationManager;
    }

    public function onSubscriptionCreated(SubscriptionCreatedEvent $event)
    {
        $subscription = $event->getSubscription();
        $subscription->setHash(uniqid('',true));

        $this->activationManager->sendActivationEmail($subscription);

        $event->setSubscription($subscription);
    }

    public static function getSubscribedEvents()
    {
        return [
            SubscriptionCreatedEvent::NAME => 'onSubscriptionCreated',
        ];
    }
}
