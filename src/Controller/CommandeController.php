<?php

namespace App\Controller;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CommandeController extends AbstractController
{
    /**
     * @Route("/admin/commande", name="commande")
     */
    public function index(CommandeRepository $repository)
    {   $commandes = $repository->findAll();
        return $this->render('commande/index.html.twig',[
            'commande' => $commandes,
        ]);
       
    }
     /**
     * @Route("/admin/ajoutcommande", name="addcommande")
     */
    public function Ajoutcommande(Request $request)
    {   $c = new Commande();
        $form = $this->createForm(CommandeType::class,$c);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            

            $em = $this->getDoctrine()->getManager();
            $em->persist($c);//ajout base
            $em->flush();// actualiser
            $this->addFlash('success','Ajout réussite');
            return $this->redirectToRoute("commande");
        }

        return $this->render('commande/ajoutcommande.html.twig', ['f' => $form->createView()]);
     
       
    }
      /**
     * @Route("/admin/commande/modifcommande/{id}", name="updatecommande")
     */
    public function modifiercommande(Request $request,$id,CommandeRepository $repository)
    {
        $c = $repository->find($id);
        $form = $this->createForm(CommandeType::class,$c);

        $form->handleRequest($request); //hydratation
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success','Modification réussite');
            return $this->redirectToRoute("commande");
        }

        return $this->render('commande/ajoutcommande.html.twig', [
            'f' => $form->createView(), 'titre' => 'Modifier une commande'
        ]);
    }
    /**
     * @Route("/admin/commande/supp/{id}", name="deletecommande")
     */
    public function supprimer(Commande $c)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($c);
        $em->flush();
        $this->addFlash('success','Suppression réussite');
        return $this->redirectToRoute("commande");
    }

}
