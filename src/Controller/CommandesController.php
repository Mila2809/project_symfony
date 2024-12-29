<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Commandes;
use App\Form\CommandesType;
use App\Entity\ContenuPanier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

// Controller lié aux pages des commandes et du panier
#[Route('/commandes')]
final class CommandesController extends AbstractController
{

    #region All

    // Liste des commandes d'un utilisateur (et du panier en cours)
    #[Route('/all', name: 'app_commandes_all', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        // Récupération des commandes de l'utilisateur connecté
        $user = $this->getUser();
        $commandes = $em->getRepository(Commandes::class)->findBy([
            'Utilisateur' => $user,
        ]);

        // Redirection vers la page
        return $this->render('commandes/all.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #endregion



    #region Show

    // Liste détaillé des articles de la commandes souhaitée
    #[Route('/{id}', name: 'app_commandes_show', methods: ['GET', 'POST'])]
    public function show(Request $request, EntityManagerInterface $em, Commandes $commandes, TranslatorInterface $translator): Response
    {
        // Récupération du contenu de la commandes
        $contenuCommande = $em->getRepository(ContenuPanier::class)->findBy([
            'commandes' => $commandes,
        ]);

        // Calcul du prix total de la commande
        $total = 0;
        foreach ($contenuCommande as $produit) {
            $prix = $produit->getProduit()->getPrix();
            $quantite = $produit->getQuantite();
            $totalProduit = $prix * $quantite;
            $total = $total + $totalProduit;
        }

        // Création du formulaire pour payer la commande
        $form = $this->createForm(CommandesType::class, $commandes);
        $form->handleRequest($request);

        // Payement de la commandes
        if ($form->isSubmitted() && $form->isValid()) {
            // Réduction des stocks des produits payés
            foreach ($contenuCommande as $produit) {
                $quantite = $produit->getQuantite();
                $stock = $produit->getProduit()->getStock();
                $newStock = $stock - $quantite;
                $produit->getProduit()->setStock($newStock);
            }
            
            // Archivage et envoie de la commande à la BDD
            $commandes->setDateAchat(new \DateTime());
            $em->flush();

            // Message flash
            $this->addFlash('success', $translator->trans('commandes.payement'));

            // Redirection vers la page
            return $this->redirectToRoute('app_commandes_all');
        }

        // Redirection vers la page
        return $this->render('commandes/show.html.twig', [
            'contenuCommande' => $contenuCommande,
            'commandes' => $commandes,
            'form' => $form,
            'total' => $total,
        ]);
    }

    #endregion



    #region Delete

    // Chemin de suppression d'un produit de la commande
    #[Route('/delete/{id}', name: 'app_commandes_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $em, ContenuPanier $contenuPanier, TranslatorInterface $translator): Response
    {
        // Suppression du produit
        if ($this->isCsrfTokenValid('delete' . $contenuPanier->getId(), $request->request->get('csrf'))) {
            $em->remove($contenuPanier);
            $em->flush();
        }

        // Message flash
        $this->addFlash('success', $translator->trans('commandes.suppression'));

        // Redirection vers la page
        return $this->redirectToRoute('app_commandes_all');
    }

    #endregion

}
