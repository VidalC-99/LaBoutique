<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderValidateController extends AbstractController
{
    #[Route('/commande/merci/{stripeSessionId}', name: 'app_order_validate')]
    public function index($stripeSessionId, ManagerRegistry $doctrine, Cart $cart): Response
    {
        $entityManager = $doctrine->getManager();
        $order = $entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$order){
            return $this->redirectToRoute('home');
        }

        if($order->getState() == 0){

            $cart->remove();
            $order->setState(1);
            $entityManager->flush();
        }
        //dd($order);
        return $this->render('order_validate/index.html.twig', [
            'order' => $order
        ]);
    }
}
