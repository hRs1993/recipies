<?php


namespace App\Services\Subscription;


use App\Entity\Subscriber;
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
        $activationURL = $this->urlGenerator->generate('subscription_activate', [
            'hash' => $subscription->getHash(),
            'email' => $subscription->getEmail()
        ],UrlGeneratorInterface::ABSOLUTE_URL);

        $email = (new Email())
            ->to($subscription->getEmail())
            ->from(new Address('makeit@cook.pl', 'Make it cook!'))
            ->subject('Activate your subscription')
            ->html(sprintf('<a href="%s">Activate</a>', $activationURL));

        $this->mailer->send($email);
    }
}