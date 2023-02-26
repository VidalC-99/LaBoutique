<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\Header;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->findByIsBest(1);
        $headers = $entityManager->getRepository(Header::class)->findAll();
        //$mail = new Mail();
        //$mail->send('laboutiquecosme@gmail.com', 'Vidxx', 'mail de test', 'content de test');

        //dd($mail);

        return $this->render('home/index.html.twig', [
            'product' => $product,
            'headers' => $headers
        ]);
    }
}