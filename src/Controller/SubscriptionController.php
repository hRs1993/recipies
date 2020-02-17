<?php

namespace App\Controller;

use App\Entity\Subscriber;
use App\Form\SubscribeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{
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
    public function subscribe(Request $request, EntityManagerInterface $entityManager)
    {
        $subscriber = new Subscriber();
        $subscriberForm = $this->createForm(SubscribeType::class, $subscriber);
        $subscriberForm->handleRequest($request);
        if ($subscriberForm->isSubmitted() && $subscriberForm->isValid()) {
            $subscriber = $subscriberForm->getData();
            $entityManager->persist($subscriber);
            $entityManager->flush();

            $this->addFlash('info', 'You have subscribed Make it cook');
        }

        return $this->redirectToRoute('subscription');
    }
}
