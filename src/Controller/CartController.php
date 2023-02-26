<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/mon-panier', name: 'cart')]
    public function index(Cart $cart, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $cartComplete = [];
        //dd($cart->get());
        if ($cart->get()) {
            foreach ($cart->get() as $id => $quantity) {
                $productComplet = $entityManager
                    ->getRepository(Product::class)
                    ->findOneById($id);

                if (!$productComplet) {
                    $cart->delete($id);
                    continue;
                }
                $cartComplete[] = [
                    'product' => $productComplet,
                    'quantity' => $quantity,
                ];
            }
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cartComplete,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'add_to_cart')]
    public function add(Cart $cart, $id): Response
    {
        $cart->add($id);
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/remove', name: 'remove_my_cart')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();
        return $this->redirectToRoute('app_product');
    }

    #[Route('/cart/delete/{id}', name: 'remove_to_cart')]
    public function delete(Cart $cart, $id): Response
    {
        $cart->delete($id);
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/decrease/{id}', name: 'decrease_to_cart')]
    public function decrease(Cart $cart, $id): Response
    {
        $cart->decrease($id);
        return $this->redirectToRoute('cart');
    }
}
