<?php

namespace App\Controller;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/admin/categorie", name="categorie")
     */
    public function index(CategorieRepository $repository)
    {
        $categories=$repository->findAll();
        return $this->render('categorie/index.html.twig', [
            'categorie' => $categories,
        ]);
    }
     /**
     * @Route("/admin/categorie/ajoutcategorie", name="addcategorie")
     */
    public function AjoutCategorie(Request $request)
    {   $cat = new Categorie();
        $form = $this->createForm(CategorieType::class,$cat);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cat);
            $em->flush();
            $this->addFlash('success','Ajout avec succéss');
            return $this->redirectToRoute("categorie");
        }

        return $this->render('categorie/ajoutcategorie.html.twig', ['f' => $form->createView()]);
     
       
    }
     /**
     * @Route("/admin/categorie/modifcategorie/{id}", name="updatecategorie")
     */
    public function modifierclient(Request $request,$id,CategorieRepository $repository)
    {
        $cat = $repository->find($id);
        $form = $this->createForm(CategorieType::class,$cat);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success','Modification réussite');
            return $this->redirectToRoute("categorie");
        }

        return $this->render('categorie/ajoutcategorie.html.twig', [
            'f' => $form->createView(), 'titre' => 'Modifier une categorie'
        ]);
    }
    
    /**
     * @Route("/admin/categorie/supp/{id}", name="deletecategorie")
     */
    public function supprimer(Categorie $c)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($c);
        $em->flush();
        $this->addFlash('success','Suppression réussite');
        return $this->redirectToRoute("categorie");
    }
}
