<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    #[Route('/account/edit-password', name: 'app_account_password')]
    public function index(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManger): Response
    {
       # $user = $this->getUser();
        #$form = $this->createForm(ChangePasswordType::class, $user);

        #$form->handleRequest($request);
        $user = new User;

        $notification = null;


        //$user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        //$entityManger = $doctrine->getManager();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $old_pwd = $form->get('old_password')->getData();
            if ($hasher->isPasswordValid($user, $old_pwd)){
                $new_pwd = $form->get('new_password')->getData();
                $password = $hasher->hashPassword($user, $new_pwd);
                $user->setPassword($password);
                $entityManger->persist($user);
                $entityManger->flush();
                $notification = "Votre mot de passe a bien été mis à jour";
            }
            else{
                $notification = "Le mot de passe saisie est incorrect";
            }
        }
        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
