<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


class RegisterController extends AbstractController
{
    /*private $entityManager;

    public function _construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }*/

    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entity): Response
    {

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form -> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //$user = $form->getData();
            
            $entity->persist($user);
            $entity->flush();
            
            return $this->redirectToRoute("app_login");

        }

        return $this->render('register/index.html.twig', [
            'form' => $form -> createView()
        ]);
    }
}
