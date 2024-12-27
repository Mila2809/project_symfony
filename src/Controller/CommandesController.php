<?php

namespace App\Controller;

use App\Entity\Commandes;
use App\Entity\Utilisateur;
use App\Form\CommandesType;
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
        $form = $this->createForm(CommandesType::class, $commandes);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em->persist($commandes);
            $em->flush();
            return $this->redirectToRoute('app_commandes');
        }

        $commandes = $em->getRepository(Commandes::class)->findAll();
        return $this->render('commandes/index.html.twig', [
            'commandes' => $commandes,
            'form' => $form,
        ]);
    }
}
