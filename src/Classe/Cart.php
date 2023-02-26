<?php

namespace App\Classe;

//use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{

    //private SessionInterface $session;
    private RequestStack $requestStack;

    public function __Construct(/*SessionInterface $session, */RequestStack $requestStack){
        //$this->session = $session;
        $this->requestStack = $requestStack;
    }

    public function add($id)
    {
        /*$this->session->set('cart', [
            [
            'id' => $id,
            'quantity' => 1
            ]
        ]);*/
        $cart = $this->requestStack->getSession()->get('cart', []);
        if(!empty($cart[$id])){
            $cart[$id]++;
        }else{
            $cart[$id] = 1;
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function remove()
    {
        //return $this->session->remove('cart');
        return $this->requestStack->getSession()->remove('cart');
    }

    public function get(){
        //return $this->session->get('cart');
        return $this->requestStack->getSession()->get('cart');

    }

    public function delete($id){
        $cart = $this->requestStack->getSession()->get('cart', []);
        unset($cart[$id]);
        return $this->requestStack->getSession()->set('cart', $cart);
    }

    public function decrease($id){
        $cart = $this->requestStack->getSession()->get('cart', []);
        if ($cart[$id] > 1){
            $cart[$id]--;
        }else{
            unset($cart[$id]);
        }
        return $this->requestStack->getSession()->set('cart', $cart);
    }

    public function getFull(ManagerRegistry $doctrine){
        $entityManger = $doctrine->getManager();
        $cartComplet =[];
        if($this->get()){
            foreach ($this->get() as $id => $quantity){
                $product_object = $entityManger->getRepository(Product::class)->findOneById($id);
                if (!$product_object){
                    $this->delete($id);
                    continue;
                }

                $cartComplet [] = [
                    'product' => $product_object,
                    'quantity' => $quantity
                ];
            }
        }
        return $cartComplet;
    }

}