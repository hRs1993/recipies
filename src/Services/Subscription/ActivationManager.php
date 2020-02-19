<?php


namespace App\Services\Subscription;


use App\Entity\Subscriber;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ActivationManager
{
    /**
     * @var Producer
     */
    private $producer;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(ContainerInterface $container, UrlGeneratorInterface $urlGenerator)
    {
        $this->producer = $container->get('old_sound_rabbit_mq.mailer_producer');
        $this->urlGenerator = $urlGenerator;
    }

    public function sendActivationEmail(Subscriber $subscription)
    {
        $email = (new TemplatedEmail())
            ->to($subscription->getEmail())
            ->from(new Address('makeit@cook.pl', 'Make it cook!'))
            ->subject('Activate your subscription')
            ->htmlTemplate('emails/subscription/activate.html.twig')
            ->context([
                'subscription' => $subscription
            ]);

        $this->producer->publish(serialize($email));
    }
}