<?php

namespace App\Controller;

use App\Entity\Commandes;
use App\Entity\ContenuPanier;
use App\Form\CommandesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commandes')]
final class CommandesController extends AbstractController
{
    #[Route('/all', name: 'app_commandes_all', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $commandes = $em->getRepository(Commandes::class)->findBy([
            'Utilisateur' => $user,
        ]);

        return $this->render('commandes/all.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/{id}', name: 'app_commandes_show', methods: ['GET', 'POST'])]
    public function show(Request $request, EntityManagerInterface $em, Commandes $commandes): Response
    {
        $panier = $em->getRepository(ContenuPanier::class)->findBy([
            'commandes' => $commandes,
        ]);

        $form = $this->createForm(CommandesType::class, $commandes);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            $commandes->setDateAchat(new \DateTime());
            $em->flush();

            return $this->redirectToRoute('app_commandes_all');
        }

        return $this->render('commandes/show.html.twig', [
            'panier' => $panier,
            'commandes' => $commandes,
            'form' => $form,
        ]);
    }
}
