<?php

namespace App\Services\Subscription;

use App\Entity\Recipe;
use App\Entity\Subscriber;
use App\Repository\SubscriberRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mime\Address;

class EmailNotifier
{
    /**
     * @var SubscriberRepository
     */
    private $subscriberRepository;

    /**
     * @var \OldSound\RabbitMqBundle\RabbitMq\Producer
     */
    private $producer;

    public function __construct(ContainerInterface $container, SubscriberRepository $subscriberRepository)
    {
        $this->subscriberRepository = $subscriberRepository;
        $this->producer = $container->get('old_sound_rabbit_mq.mailer_producer');
    }

    public function notifyRecipeCreated(Recipe $recipe)
    {
        $subscribers = $this->subscriberRepository->findBy([
            'activated' => true
        ]);

        foreach ($subscribers as $subscriber) {
            $this->notifySubscriberRecipeCreated($subscriber, $recipe);
        }
    }

    private function notifySubscriberRecipeCreated(Subscriber $subscriber, Recipe $recipe)
    {
        $mail = (new TemplatedEmail())
            ->to($subscriber->getEmail())
            ->from(new Address('makeit@cook.pl', 'Make it cook!'))
            ->subject(sprintf("Recipe `%s` is available", $recipe->getName()))
            ->htmlTemplate('emails/recipes/created.html.twig')
            ->context([
                'subscriber' => $subscriber,
                'recipe' => $recipe
            ]);


        $this->producer->publish(serialize($mail));
    }
}