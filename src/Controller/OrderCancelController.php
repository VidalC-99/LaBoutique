<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderCancelController extends AbstractController
{
    #[Route('/commande/error/{stripeSessionId}', name: 'app_order_cancel')]
    public function index($stripeSessionId, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $order = $entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$order){
            return $this->redirectToRoute('home');
        }
        return $this->render('order_cancel/index.html.twig', [
            'order' => $order
        ]);
    }
}
