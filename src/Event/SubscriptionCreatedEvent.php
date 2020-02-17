<?php


namespace App\Event;


use App\Entity\Subscriber;
use Symfony\Contracts\EventDispatcher\Event;

class SubscriptionCreatedEvent extends Event
{
    public const NAME = 'subscription.created';
    /**
     * @var Subscriber
     */
    private $subscriber;

    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    public function getSubscription()
    {
        return $this->subscriber;
    }

    public function setSubscription(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }
}