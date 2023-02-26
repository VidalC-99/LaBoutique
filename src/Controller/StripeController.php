<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'app_stripe_cs')]
    public function index(Cart $cart, ManagerRegistry $doctrine, $reference): Response
    {
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';
        $product_for_stripe = [];

        $entityManager = $doctrine->getManager();

        $order = $doctrine->getRepository(Order::class)->findOneByReference($reference);
        //dd($order);

        foreach ($order->getOrderDetails()->getValues() as $product ) {
            $product_object = $doctrine->getRepository(Product::class)->findOneByName($product->getProduct());

            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' =>[
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN."/upload/images/products/".$product_object->getIllustration()]
                    ],

                ],
                'quantity' => $product->getQuantity(),
            ];
        }

        $product_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice(),
                'product_data' =>[
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN]
                ],

            ],
            'quantity' => 1
        ];

        // dd($product_for_stripe);


        Stripe::setApiKey('sk_test_51LWnG3HWZy2YBU7YhcBUG567A5TJcjqDqGX3O2Dp6JYqAKAKhlKbbIMUiYBnKFl60kCLGcPJk8T6P7HW6SfmwA4800ORYCXygb');

        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $product_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/error/{CHECKOUT_SESSION_ID}',

        ]);

        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        $response = new JsonResponse(['id' => $checkout_session->id]);

        return $response;
    }
}
