<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Form\OrderType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/commande', name: 'app_order')]
    public function index(Cart $cart, ManagerRegistry $doctrine, Request $request): Response
    {
        if (!$this->getUser()->getAddresses()->getValues()){
            return $this->redirectToRoute('account_add_address');
        }


        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);


        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull($doctrine)
        ]);
    }

    #[Route('/commande/recapitulatif', name: 'app_order_recap', methods:'POST' )]
    public function add(Cart $cart, ManagerRegistry $doctrine, Request $request): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $entityManager = $doctrine->getManager();

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()){
            $date = new \DateTimeImmutable();
            $carriers = $form->get('carriers')->getData();
            $delivery = $form->get('adresses')->getData();
            $delivery_content = $delivery->getFirstname().''.$delivery->getLastname();
            $delivery_content .= '</br>'.$delivery->getPhone();
            if($delivery->getCompany()){
                $delivery_content .= '</br>'.$delivery->getCompany();
            }
            $delivery_content .= '</br>'.$delivery->getAddress();
            $delivery_content .= '</br>'.$delivery->getPostal().' '.$delivery->getCity();
            $delivery_content .= '</br>'.$delivery->getCountry();

            //dd($delivery_content);
            $order = new Order();
            $reference = $date->format('dmy').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setState(0);

            $entityManager->persist($order);

            foreach ($cart->getFull($doctrine) as $product ) {
                $orderDetail = new OrderDetail();
                $orderDetail->setMyOrder($order);
                $orderDetail->setProduct($product['product']->getName());
                $orderDetail->setQuantity($product['quantity']);
                $orderDetail->setPrice($product['product']->getPrice());
                $orderDetail->setTotal($product['product']->getPrice() * $product['quantity']);
                $entityManager->persist($orderDetail);

            }


            $entityManager->flush();
            return $this->render('order/add.html.twig', [
                'cart' => $cart->getFull($doctrine),
                'carrier' => $carriers,
                'delivery' => $delivery_content,
                'reference' => $order->getReference()

            ]);
        }


        return  $this->redirectToRoute('cart');
    }
}
