<?php


namespace App\Services\Subscription;


use App\Entity\Subscriber;
use App\Messenger\Message\EmailMessage;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
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
    /**
     * @var MessageBusInterface
     */
    private $messageBus;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(MailerInterface $mailer, UrlGeneratorInterface $urlGenerator, MessageBusInterface $messageBus, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->urlGenerator = $urlGenerator;
        $this->messageBus = $messageBus;
        $this->logger = $logger;
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

        $this->messageBus->dispatch(EmailMessage::create($email));

        $this->logger->info('Activation email has been send to queue');
        // $this->mailer->send($email);
    }
}