<?php

namespace App\Controller;

use App\Entity\Commandes;
use App\Entity\ContenuPanier;
use App\Entity\Produit;
use App\Form\CommandesType;
use App\Form\ContenuPanierType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/all', name: 'app_produit_all', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }
    
    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $em, Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('Photo')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // METTRE UN MESSAGE D'ERREUR
                }

                $produit->setPhoto($newFilename);
            }
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('app_produit_all');
        }

        return $this->render('produit/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_produit_selected', methods: ['GET', 'POST'])]
    public function selected(EntityManagerInterface $em, Request $request, Produit $produit): Response
    {

        $user = $this->getUser();
        
        $commandes = new Commandes();
        $panier = new ContenuPanier();

        $userPanier = $em->getRepository(Commandes::class)->findOneBy([
            'Utilisateur' => $user,
            'DateAchat' => null,
        ]);

        $commandes->setUtilisateur($user);
        $panier->setProduit($produit);
        $panier->setCommandes($userPanier);

        $commandesForm = $this->createForm(CommandesType::class, $commandes);
        $panierForm = $this->createForm(ContenuPanierType::class, $panier);

        $commandesForm->handleRequest($request);
        if($commandesForm->isSubmitted()){
            $em->persist($commandes);
            $em->flush();
            return $this->redirectToRoute('app_produit_all');
        }

        $panierForm->handleRequest($request);
        if($panierForm->isSubmitted()){
            if (!$userPanier) {
                $userPanier = new Commandes();
                $userPanier->setUtilisateur($user);
                $em->persist($userPanier);
                $em->flush();
            }

            $produitPanier = $em->getRepository(ContenuPanier::class)->findOneBy([
                'commandes' => $userPanier,
                'Produit' => $produit,
            ]);

            if ($produitPanier){
                $newQuantity = $produitPanier->getQuantite() + $panier->getQuantite();
                $panier->setQuantite($newQuantity);
                $em->remove($produitPanier);
                $em->persist($panier);
                $em->flush();
            } else {
                $panier->setProduit($produit);
                $panier->setCommandes($userPanier);
                $em->persist($panier);
                $em->flush();
            }
                return $this->redirectToRoute('app_produit_all');
        }

        $commandes = $em->getRepository(ContenuPanier::class)->findAll();
        $panier = $em->getRepository(ContenuPanier::class)->findAll();

        return $this->render('produit/selected.html.twig', [
            'produit' => $produit,
            'panierForm' => $panierForm,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $em, Produit $produit): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('csrf'))) {
            $em->remove($produit);
            $em->flush();
        }
        return $this->redirectToRoute('app_produit_all');
    }

    #[Route('/edit/{id}', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

            
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('Photo')->getData();

            unlink(__DIR__ . '/../../public/uploads/' . $produit->getPhoto());

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {}
                
                $produit->setPhoto($newFilename);
            }

            $entityManager->flush();
            return $this->redirectToRoute('app_produit_selected', ['id' => $produit->getId()]);
        }
            return $this->render('produit/edit.html.twig', [
                'form' => $form->createView(),
                'produit' => $produit,
            ]);
    }
}
