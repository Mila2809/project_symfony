<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Form\UtilisateurType;

// Controller lié aux pages utilisateur
#[Route('/utilisateur')]
final class UtilisateurController extends AbstractController
{

    #region Create

    // Création d'un utilisateur
    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // Création du formulaire
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        // Envoie du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération du mot de passe
            /** @var string $plainPassword */
            $plainPassword = $form->get('Password')->getData();
            // Hachage du mot de passe
            $utilisateur->setPassword($userPasswordHasher->hashPassword($utilisateur, $plainPassword));
            
            // Envoie du formulaire avec le MDP haché à la BDD
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            // Redirection vers la page
            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        // Redirection vers la page
        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #endregion



    #region Read All

    // Affiche de tous les utilisateurs
    #[Route('/all', name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        // Redirection vers la page
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    #endregion



    #region Read One

    // Affiche d'un utilisateur
    #[Route('/{id}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        // Redirection vers la page
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #endregion



    #region Update

    // Modification d'un utilisateur
    #[Route('/{id}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        // Création du formulaire
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        // Envoie du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Envoie du formulaire à la BDD
            $entityManager->flush();

            // Redirection vers la page
            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        // Redirection vers la page
        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #endregion



    #region Delete

    // Chemin de suppression d'un utilisateur
    #[Route('/{id}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        // Suppression de l'utilisateur
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        // Redirection vers la page
        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }

    #endregion

}
