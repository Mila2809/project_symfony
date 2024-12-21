<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);
        if($form->isSubmitted()){
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
                                // ...
                            }
            
                            $produit->setPhoto($newFilename);
                        }
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('app_produit');
        }




        
        // $produit = $em->getRepository(Category::class)->findAll();
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
            'form' => $form,
            
        ]);
    }
}
