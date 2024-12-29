<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Controller de la page d'accueil
class IndexController extends AbstractController{

    #region Index

    // Affichage de la page d'accueil
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('index/home.html.twig', [
        ]);
    }

    #endregion

}