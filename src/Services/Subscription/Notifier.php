<?php

namespace App\Services\Subscription;

use App\Entity\Recipe;
use App\Entity\Subscriber;
use App\Repository\SubscriberRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class Notifier
{
    /**
     * @var SubscriberRepository
     */
    private $subscriberRepository;
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(SubscriberRepository $subscriberRepository, MailerInterface $mailer)
    {
        $this->subscriberRepository = $subscriberRepository;
        $this->mailer = $mailer;
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
        $mail = (new Email())
                    ->to($subscriber->getEmail())
                    ->from(new Address('makeit@cook.pl', 'Make it cook!'))
                    ->subject(sprintf("Recipe `%s` is available", $recipe->getName()))
                    ->text(sprintf("Recipe `%s` is available! %s", $recipe->getName(), $recipe->getDescription()));

        $this->mailer->send($mail);
    }
}