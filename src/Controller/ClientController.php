<?php

namespace App\Controller;
use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ClientController extends AbstractController
{
     /**
     * @Route("/admin/client", name="client")
     */
    public function index(ClientRepository $repository)
    {   $clients = $repository->findAll();
        return $this->render('client/index.html.twig', [
            'client' => $clients,
        ]);
       
    }
     
        /**
     * @Route("/admin/client/ajoutclient", name="addclient")
     */
    public function AjoutClient(Request $request )
    {   $c = new Client();
        $form = $this->createForm(ClientType::class,$c);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($c);
            $em->flush();
            $this->addFlash('success','Ajout réussite');
            return $this->redirectToRoute("client");
        }

        return $this->render('client/ajoutclient.html.twig', ['f' => $form->createView()]);
     
       
    }
     /**
     * @Route("/admin/client/modifclient/{id}", name="updateclient")
     */
    public function modifierclient(Request $request,$id,ClientRepository $repository)
    {
        $c = $repository->find($id);
        $form = $this->createForm(ClientType::class,$c);

        $form->handleRequest($request); //hydratation
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success','Modification réussite');
            return $this->redirectToRoute("client");
        }

        return $this->render('client/ajoutclient.html.twig', [
            'f' => $form->createView(), 'titre' => 'Modifier un client'
        ]);
    }


    /**
     * @Route("/admin/client/supp/{id}", name="deleteclient")
     */
    public function supprimer(Client $c)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($c);
        $em->flush();
        $this->addFlash('success','Suppression réussite');
        return $this->redirectToRoute("client");
    }
}
