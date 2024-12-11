<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaniersController extends AbstractController
{
    #[Route('/panier', name: 'view_panier')]
    public function panier(): Response
    {
        return $this->render('paniers/panier.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }
}
