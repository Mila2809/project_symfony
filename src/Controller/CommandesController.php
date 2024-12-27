<?php

namespace App\Controller;

use App\Entity\Commandes;
use App\Entity\ContenuPanier;
use App\Entity\Utilisateur;
use App\Form\CommandesType;
use App\Form\ContenuPanierType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class CommandesController extends AbstractController
{
    #[Route('/commandes', name: 'app_commandes', methods: ['GET', 'POST'])]
    public function index(UtilisateurRepository $utilisateurRepository, EntityManagerInterface $em, Request $request): Response
    {   
        $user = $this->getUser();
        
        $commandes = new Commandes();
        $commandes->setUtilisateur($user);
        $commandesForm = $this->createForm(CommandesType::class, $commandes);

        $commandesForm->handleRequest($request);
        if($commandesForm->isSubmitted()){
            $em->persist($commandes);
            $em->flush();
            return $this->redirectToRoute('app_commandes');
        }

        $panier = new ContenuPanier();
        $panierForm = $this->createForm(ContenuPanierType::class, $panier);
        
        $panierForm->handleRequest($request);
        if($panierForm->isSubmitted()){
            $em->persist($panier);
            $em->flush();
            return $this->redirectToRoute('app_commandes');
        }

        $commandes = $em->getRepository(ContenuPanier::class)->findAll();
        $panier = $em->getRepository(ContenuPanier::class)->findAll();
        return $this->render('commandes/index.html.twig', [
            'commandesForm' => $commandesForm,
            'panierForm' => $panierForm,
        ]);
    }
}
