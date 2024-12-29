<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;

// Controller lié à la page du compte et de la déconnexion
class SecurityController extends AbstractController
{

    #region Log in

    // Connexion à un compte
    #[Route(path: '/login', name: 'app_login' )]
    public function login(AuthenticationUtils $authenticationUtils, Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        // Récupération d'une erreur de connexion
        $error = $authenticationUtils->getLastAuthenticationError();

        // Récupération du dernier nom d'utilisateur utilisé
        $lastUsername = $authenticationUtils->getLastUsername();

        // Création du formulaire pour modifié les informations de son compte
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        // Envoie du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Envoie du formulaire à la BDD
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            // Message flash
            $this->addFlash('success', $translator->trans('account.login'));

            // Redirection vers la page
            return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
        }

        // Redirection vers la page
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'form' => $form,
        ]);
    }

    #endregion



    #region Account

    // Affichage des informations du compte
    #[Route(path: '/account', name: 'app_account', methods: ['GET', 'POST'] )]
    public function account(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        // Création du formulaire
        $utilisateur = $this->getUser();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        // Envoie du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Envoie du formulaire à la BDD
            $entityManager->flush();

            // Message flash
            $this->addFlash('success', $translator->trans('account.update'));

            // Redirection vers la page
            return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
        }

        // Redirection vers la page
        return $this->render('security/account.html.twig', [
            'form' => $form,
        ]);
    }

    #endregion



    #region Log out

    // Chemin de déconnexion
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(TranslatorInterface $translator): void
    {
        // Message flash
        $this->addFlash('success', $translator->trans('account.logout'));
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #endregion
}
