<?php

namespace App\EventSubscriber;

use App\Event\RecipeCreatedEvent;
use App\Services\Subscription\Notifier;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RecipeCreatedSubscriber implements EventSubscriberInterface
{
    /**
     * @var Notifier
     */
    private $subscriptionNotifier;

    public function __construct(Notifier $subscriptionNotifier)
    {
        $this->subscriptionNotifier = $subscriptionNotifier;
    }

    public function onRecipeCreated(RecipeCreatedEvent $event)
    {
        $recipe = $event->getRecipe();

        $this->subscriptionNotifier->notifyRecipeCreated($recipe);
    }

    public static function getSubscribedEvents()
    {
        return [
            RecipeCreatedEvent::NAME => 'onRecipeCreated',
        ];
    }
}
