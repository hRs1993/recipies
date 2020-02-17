<?php

namespace App\EventSubscriber;

use App\Event\RecipeCreatedEvent;
use App\Services\Subscription\EmailNotifier;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RecipeCreatedSubscriber implements EventSubscriberInterface
{
    /**
     * @var EmailNotifier
     */
    private $subscriptionNotifier;

    public function __construct(EmailNotifier $subscriptionNotifier)
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
