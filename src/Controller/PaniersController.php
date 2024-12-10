<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaniersController extends AbstractController
{
    #[Route('/paniers', name: 'app_paniers')]
    public function index(): Response
    {
        return $this->render('paniers/index.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }
}
