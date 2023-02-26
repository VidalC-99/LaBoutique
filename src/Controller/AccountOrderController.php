<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountOrderController extends AbstractController
{
        #[Route('/account/mes-commandes', name: 'app_account_order')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager =  $doctrine->getManager();
        $orders = $entityManager->getRepository(Order::class)->findSuccesOrdes($this->getUser());
        return $this->render('account/order.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/account/mes-commandes/{reference}', name: 'app_account_order_show')]
    public function show(ManagerRegistry $doctrine, $reference): Response
    {
        $entityManager =  $doctrine->getManager();
        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        if (!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('app_account_order');
        }

        return $this->render('account/order-show.html.twig', [
            'order' => $order
        ]);
    }
}
