<?php

namespace App\Controller;
use App\Repository\ProduitRepository;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Form\ClientPartType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ClientPartController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function index()
    {
        return $this->render('client_part/index.html.twig', [
            'controller_name' => 'ClientPartController',
        ]);
    }
    /**
     * @Route("/produits", name="afficheproduit")
     */
    public function affiche(ProduitRepository $repository)
    { 
        {   $produits = $repository->findAll();
            return $this->render('client_part/afficheproduit.html.twig', [
                'produit' => $produits,
            ]);
           
        }
    }
    /**
     * @Route("/produits/commander", name="commander")
     */
    public function Ajoutcommande(Request $request)
    {   $c = new Commande();
        $form = $this->createForm(CommandeType::class,$c);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            

            $em = $this->getDoctrine()->getManager();
            $em->persist($c);
            $em->flush();
            $this->addFlash('success','Ajout réussite');
            return $this->redirectToRoute("commander");
        }

        return $this->render('client_part/commande.html.twig', ['f' => $form->createView()]);
     
       
    }
}
