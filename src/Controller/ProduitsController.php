<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProduitsController extends AbstractController
{
    #[Route('/produits', name: 'app_produits')]
    public function produits(): Response
    {
        return $this->render('produits/produits.html.twig', [
            'controller_name' => 'ProduitsController',
        ]);
    }

    #[Route('/produit', name: 'view_produit')]
    public function produit(): Response
    {
        return $this->render('produits/produit.html.twig', [
            'controller_name' => 'ProduitsController',
        ]);
    }
}
