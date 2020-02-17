<?php


namespace App\Services\Subscription;


use App\Entity\Subscriber;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ActivationManager
{
    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(MailerInterface $mailer, UrlGeneratorInterface $urlGenerator)
    {
        $this->mailer = $mailer;
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

        $this->mailer->send($email);
    }
}