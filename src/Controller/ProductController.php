<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->findAll();

        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $product = $entityManager->getRepository(Product::class)->findWithSearch($search);
        }

        return $this->render('product/index.html.twig', [
            'products' => $product,
            'form' => $form->createView()
        ]);
    }

    #[Route('/productdetail/{slug}', name: 'app_productdetail')]
    public function Show(ManagerRegistry $doctrine, $slug): Response
    {
        $entityManager = $doctrine->getManager();
        $productdetal = $entityManager->getRepository(Product::class)->findOneBySlug($slug);
        $product = $entityManager->getRepository(Product::class)->findByIsBest(1);

        if(!$productdetal){
            return $this->redirectToRoute('app_product');
        }

        return $this->render('product/show.html.twig', [
            'productdetail' => $productdetal,
            'product' => $product
        ]);
    }
}
