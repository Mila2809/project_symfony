<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Commandes;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Controller de la page SUPER_ADMIN
class SuperAdminController extends AbstractController{

    #region SuperAdmin

    // Affichage de la page SUPER_ADMIN
    #[Route('/superadmin', name: 'app_super_admin')]
    public function index(EntityManagerInterface $em, UtilisateurRepository $utilisateurRepository): Response
    {
        // Récupération des paniers non-payé
        $paniers = $em->getRepository(Commandes::class)->findBy([
            'DateAchat' => null,
        ]);

        // Redirection vers la page
        return $this->render('admin/superAdmin.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
            'paniers' => $paniers,
        ]);
    }

    #endregion

}