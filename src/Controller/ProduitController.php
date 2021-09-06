<?php

namespace App\Controller;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;

class ProduitController extends AbstractController
{
        /**
     * @Route("/admin/produit", name="produit")
     */
    public function index(ProduitRepository $repository)
    {   $produits = $repository->findAll();
        return $this->render('produit/index.html.twig',[
            'produit' => $produits,
        ]);
       
    }
       /**
     * @Route("/admin/produit/ajoutproduit", name="addproduit")
     */
    public function AjoutProduit(Request $request)
    {   $p = new Produit();
        $form = $this->createForm(ProduitType::class,$p);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $file = new File($p->getImage());
            $fileName = md5 (uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'), $fileName);
            $p->setImage($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($p);
            $em->flush();
            $this->addFlash('success','Ajout réussite');
            return $this->redirectToRoute("produit");
        }

        return $this->render('produit/ajoutproduit.html.twig', ['f' => $form->createView()]);
     
       
    }
     /**
     * @Route("/admin/produit/modifproduit/{id}", name="updateproduit")
     */
    public function modifierproduit(Request $request,$id,ProduitRepository $repository)
    {
        $p = $repository->find($id);
        $form = $this->createForm(ProduitType::class,$p);

        $form->handleRequest($request); //hydratation
        if($form->isSubmitted() && $form->isValid())
        {

            $file = new File($p->getImage());
            $fileName = md5 (uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'), $fileName);
            $p->setImage($fileName);


            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success','Modification réussite');
            return $this->redirectToRoute("produit");
        }

        return $this->render('produit/ajoutproduit.html.twig', [
            'f' => $form->createView(), 'titre' => 'Modifier un produit'
        ]);
    }
     /**
     * @Route("/admin/produit/supp/{id}", name="deleteproduit")
     */
    public function supprimer(Produit $p)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($p);
        $em->flush();
        $this->addFlash('success','Suppression réussite');
        return $this->redirectToRoute("produit");
    }
     /**
     * @Route("/admin/produit/recherche/{id}", name="findproduit")
     */
    public function recherche(Produit $p)
    {
        return $this->render('produit/index.html.twig', [
            'produit' => $p,
        ]);
    }
}
