<?php

namespace App\EventSubscriber;

use App\Event\RecipeCreatedEvent;
use App\Services\Subscription\EmailNotifier;
use App\Services\TagsCloudManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RecipeCreatedSubscriber implements EventSubscriberInterface
{
    /**
     * @var EmailNotifier
     */
    private $subscriptionNotifier;
    /**
     * @var TagsCloudManager
     */
    private $tagsCloudManager;

    public function __construct(EmailNotifier $subscriptionNotifier, TagsCloudManager $tagsCloudManager)
    {
        $this->subscriptionNotifier = $subscriptionNotifier;
        $this->tagsCloudManager = $tagsCloudManager;
    }

    public function onRecipeCreated(RecipeCreatedEvent $event)
    {
        $recipe = $event->getRecipe();

        $this->subscriptionNotifier->notifyRecipeCreated($recipe);
        $this->tagsCloudManager->clearCache();
    }

    public static function getSubscribedEvents()
    {
        return [
            RecipeCreatedEvent::NAME => 'onRecipeCreated',
        ];
    }
}
