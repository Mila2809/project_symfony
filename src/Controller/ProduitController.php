<?php

namespace App\Controller;

use App\Entity\Produit;
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
    
    #[Route('/{id}', name: 'app_produit_show')]
    public function show(EntityManagerInterface $em, Request $request, Produit $produit): Response
    {
        // $produit = new Produit();
        // $form = $this->createForm(ProduitType::class, $produit);

        // $form->handleRequest($request);
        // if($form->isSubmitted() && $form->isValid()){
        //                 /** @var UploadedFile $imageFile */
        //                 $imageFile = $form->get('Photo')->getData();

        //                 if ($imageFile) {
        //                     $newFilename = uniqid().'.'.$imageFile->guessExtension();
            
        //                     try {
        //                         $imageFile->move(
        //                             $this->getParameter('upload_directory'),
        //                             $newFilename
        //                         );
        //                     } catch (FileException $e) {
        //                         // ...
        //                     }
            
        //                     $produit->setPhoto($newFilename);
        //                 }
        //     $em->persist($produit);
        //     $em->flush();
        //     return $this->redirectToRoute('app_produit');
        // }

        return $this->render('produit/index.html.twig', [
            // 'controller_name' => 'ProduitController',
            'produit' => $produit
        ]);
    }
    #[Route('/delete/{id}', name: 'app_produit_delete')]
    public function delete(Request $request, EntityManagerInterface $em, produit $produit)
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('csrf'))) {
            $em->remove($produit);
            $em->flush();
        }
        return $this->redirectToRoute('app_produit');
    }
}
