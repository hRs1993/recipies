<?php

namespace App\Controller;

use App\Entity\Subscriber;
use App\Event\SubscriptionCreatedEvent;
use App\Form\SubscribeType;
use App\Repository\SubscriberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{
    /**
     * @var SubscriberRepository
     */
    private $subscriberRepository;

    public function __construct(SubscriberRepository $subscriberRepository)
    {
        $this->subscriberRepository = $subscriberRepository;
    }

    /**
     * @Route("/subscription", name="subscription", methods={"GET"})
     */
    public function subscription()
    {
        $subscriberForm = $this->createForm(SubscribeType::class);

        return $this->render('subscriber/index.html.twig', [
            'subscribeForm' => $subscriberForm->createView()
        ]);
    }


    /**
     * @Route("/subscription/subscribe", name="subscription_subscribe", methods={"POST"})
     */
    public function subscribe(Request $request, EntityManagerInterface $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        $subscriber = new Subscriber();
        $subscriberForm = $this->createForm(SubscribeType::class, $subscriber);
        $subscriberForm->handleRequest($request);
        if ($subscriberForm->isSubmitted() && $subscriberForm->isValid()) {
            $subscriber = $subscriberForm->getData();

            $subscriberCreatedEvent = new SubscriptionCreatedEvent($subscriber);
            $eventDispatcher->dispatch($subscriberCreatedEvent, SubscriptionCreatedEvent::NAME);

            $entityManager->persist($subscriber);
            $entityManager->flush();

            $this->addFlash('info', 'You have subscribed Make it cook');
        }

        return $this->redirectToRoute('subscription');
    }

    /**
     * @Route("/subscription/activate/{email}/{hash}", name="subscription_activate", methods={"GET"})
     */
    public function activate($email, $hash, EntityManagerInterface $entityManager)
    {
        $subscriber = $this->subscriberRepository->findOneBy([
            'email' => $email,
            'hash' => $hash,
            'activated' => false
        ]);

        if (!$subscriber) {
            return $this->redirectToRoute('home');
        }

        $subscriber->setActivated(true);
        $entityManager->persist($subscriber);
        $entityManager->flush();

        $this->addFlash('info', 'Your subscription has been activated');

        return $this->redirectToRoute('home');
    }
}
