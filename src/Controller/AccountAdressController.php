<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AddressType;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAdressController extends AbstractController
{
    #[Route('/account/address', name: 'app_account_address')]
    public function index(): Response
    {

        return $this->render('account/address.html.twig');
    }

    #[Route('/account/Add_address', name: 'account_add_address')]
    public function Add(Request $request, ManagerRegistry $doctrine, Cart $cart): Response
    {
        $entityManager = $doctrine->getManager();
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $address->setUser($this->getUser());
            $entityManager->persist($address);
            $entityManager->flush();
            if($cart->get()){
                return $this->redirectToRoute('app_order');
            }else {
                return $this->redirectToRoute('app_account_address');
            }
        }


        return $this->render('account/addressadd.html.twig', [
            'form' => $form->createView()
        ]);


    }

    #[Route('/account/modifier_address/{id}', name: 'account_edit_address')]
    public function Edit(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        $entityManager = $doctrine->getManager();
        $address = $entityManager->getRepository(Address::class)->findOneById($id);

        if($address->getUser() != $this->getUser() || !$address){
            return $this->redirectToRoute('app_account_address');
        }

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();
            return $this->redirectToRoute('app_account_address');

        }


        return $this->render('account/addressadd.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/account/supp_address/{id}', name: 'account_delete_address')]
    public function delete(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        $entityManager = $doctrine->getManager();
        $address = $entityManager->getRepository(Address::class)->findOneById($id);

        if($address->getUser() == $this->getUser() && $address){
            $entityManager->remove($address);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_account_address');

    }
}
