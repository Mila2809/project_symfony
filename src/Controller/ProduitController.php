<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Commandes;
use App\Form\CommandesType;
use App\Entity\ContenuPanier;
use App\Form\ContenuPanierType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Form\ProduitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

// Controller lié aux pages des produits

class ProduitController extends AbstractController
{

    #region Create
    
    // Création d'un produit
    #[Route('/produit/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $em, Request $request, TranslatorInterface $translator): Response
    {
        // Création du formulaire
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        // Envoie du formulaire
        if($form->isSubmitted() && $form->isValid()){
            // Récupration de l'image
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('Photo')->getData();

            if ($imageFile) {
                // Renommage de l'image
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                try {
                    // Enregistrement de l'image
                    $imageFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    die($e);
                }

                // Ajout de la nouvelle image dans le formulaire
                $produit->setPhoto($newFilename);
            }
            // Envoie du formulaire à la BDD
            $em->persist($produit);
            $em->flush();

            // Message flash
            $this->addFlash('success', $translator->trans('product.created'));

            // Redirection vers la page
            return $this->redirectToRoute('app_produit_all');
        }

        // Redirection vers la page
        return $this->render('produit/new.html.twig', [
            'form' => $form,
        ]);
    }

    #endregion



    #region Read All

    // Affichage de la liste des produits
    #[Route('/', name: 'app_produit_all', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        // Redirection vers la page
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);    
    }    

    #endregion



    #region Read One

    // Affichage des détails d'un produit et ajout au panier
    #[Route('/produit/show/{id}', name: 'app_produit_selected', methods: ['GET', 'POST'])]
    public function selected(EntityManagerInterface $em, Request $request, Produit $produit, TranslatorInterface $translator): Response
    {
        // Récupération de l'utilisateur
        $user = $this->getUser();
        // Récupération du panier de l'utilisateur
        $userPanier = $em->getRepository(Commandes::class)->findOneBy([
            'Utilisateur' => $user,
            'DateAchat' => null,
        ]);
        
        // Création du formulaire de commande
        $commandes = new Commandes();
        $commandes->setUtilisateur($user);
        $commandesForm = $this->createForm(CommandesType::class, $commandes);
        $commandesForm->handleRequest($request);

        // Création du formulaire du contenu de la commande
        $contenuPanier = new ContenuPanier();
        $contenuPanier->setProduit($produit);
        $contenuPanier->setCommandes($userPanier);
        $contenuPanierForm = $this->createForm(ContenuPanierType::class, $contenuPanier);
        $contenuPanierForm->handleRequest($request);

        // Envoie du formulaire de commande à la BDD
        if($commandesForm->isSubmitted()){
            $em->persist($commandes);
            $em->flush();
            return $this->redirectToRoute('app_produit_all');
        }
        // Envoie du formulaire du contenu de la commande à la BDD
        if($contenuPanierForm->isSubmitted()){
            // Si l'utilisateur n'a pas de panier, création d'un panier
            if (!$userPanier) {
                $userPanier = new Commandes();
                $userPanier->setUtilisateur($user);
                $em->persist($userPanier);
                $em->flush();
            }

            // Recherche d'un produit identique dans la panier
            $produitPanier = $em->getRepository(ContenuPanier::class)->findOneBy([
                'commandes' => $userPanier,
                'Produit' => $produit,
            ]);
            // Si le produit est déjà dans le panier, modification de la quantité
            if ($produitPanier){
                // Calcul et modification de la quantité de produit
                $newQuantite = $produitPanier->getQuantite() + $contenuPanier->getQuantite();
                $contenuPanier->setQuantite($newQuantite);
                // Suppression de l'ancien produit
                $em->remove($produitPanier);
                // Envoie d'un nouveau produit avec la nouvelle quantité dans à la BDD
                $em->persist($contenuPanier);
                $em->flush();
            // Sinon, ajout du produit
            } else {
                // Définission du produit et du panier
                $contenuPanier->setProduit($produit);
                $contenuPanier->setCommandes($userPanier);
                // Envoie du formulaire à la BDD
                $em->persist($contenuPanier);
                $em->flush();
            }

            // Message flash
            $this->addFlash('success', $translator->trans('product.addCart'));

            // Redirection vers la page
            return $this->redirectToRoute('app_produit_all');
        }

        // Redirection vers la page
        return $this->render('produit/selected.html.twig', [
            'produit' => $produit,
            'contenuPanierForm' => $contenuPanierForm,
        ]);
    }

    #endregion



    #region Update

    // Modification d'un produit
    #[Route('/produit/edit/{id}', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        // Création du formulaire
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        // Envoie du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération de l'image
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('Photo')->getData();

            // Suppression de l'ancienne image
            unlink(__DIR__ . '/../../public/uploads/' . $produit->getPhoto());

            if ($imageFile) {
                // Renommage de l'image
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                try {
                    // Enregistrement de l'image
                    $imageFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {}
                
                // Ajout de la nouvelle image
                $produit->setPhoto($newFilename);
            }

            // Envoie du formulaire à la BDD
            $entityManager->flush();

            // Message flash
            $this->addFlash('success', $translator->trans('product.update'));

            // Redirection vers la page
            return $this->redirectToRoute('app_produit_selected', ['id' => $produit->getId()]);
        }

        // Redirection vers la page
        return $this->render('produit/edit.html.twig', [
            'form' => $form->createView(),
            'produit' => $produit,
        ]);
    }

    #endregion



    #region Delete

    // Chemin de suppression d'un produit
    #[Route('/produit/delete/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $em, Produit $produit, TranslatorInterface $translator): Response
    {
        // Suppression du produit
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('csrf'))) {
            $em->remove($produit);
            $em->flush();
        }

        // Message flash
        $this->addFlash('success', $translator->trans('product.delete'));

        // Redirection vers la page
        return $this->redirectToRoute('app_produit_all');
    }

    #endregion

}
