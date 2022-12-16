<?php

namespace App\Controller;

use App\Entity\Enclos;
use App\Form\EnclosType;
use App\Form\EnclosSupprimerType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\throwException;

class EnclosController extends AbstractController
{
    /**
     * @Route("/", name="app_enclos")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(Enclos::class);
        $enclos = $repo->findAll();

        return $this->render('enclos/index.html.twig', [
            'enclos'=>$enclos
        ]);
    }

    /**
     * @Route("/enclos/ajouter", name="app_enclos_ajouter")
     */
    public function ajouter(ManagerRegistry $doctrine, Request $request): Response
    {
        $enclos= new Enclos();
        $form=$this->createForm(EnclosType::class, $enclos);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $em->persist(($enclos));

            $em->flush();

            return $this->redirectToRoute("app_enclos");
        }

        return $this->render("enclos/ajouter.html.twig",[
            "formulaire"=>$form->createView()
        ]);
    }


    /**
     * @Route("/enclos/modifier/{id}", name="app_enclos_modifier")
     */
    public function modifier($id, ManagerRegistry $doctrine, Request $request): Response
    {
        $enclos = $doctrine->getRepository(Enclos::class)->find($id);

        if (!$enclos){
            throw $this->createNotFoundException("Pas d'enclos avec l'id $id");
        }

        $form=$this->createForm(EnclosType::class, $enclos);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $em->persist(($enclos));

            $em->flush();

            return $this->redirectToRoute("app_enclos");
        }

        return $this->render("enclos/modifier.html.twig",[
            "enclos"=>$enclos,
            "formulaire"=>$form->createView()
        ]);

    }

    /**
     * @Route("/enclos/supprimer/{id}", name="app_enclos_supprimer")
     */
    public function supprimer($id, ManagerRegistry $doctrine, Request $request): Response
    {
        $enclos = $doctrine->getRepository(Enclos::class)->find($id); // select * from catégoire where id = ...

        if (!$enclos){
            throw $this->createNotFoundException("Pas d'enclos avec l'id $id");
        }

        $form=$this->createForm(EnclosSupprimerType::class, $enclos);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $em->remove(($enclos));

            $em->flush();

            return $this->redirectToRoute("app_enclos");
        }

        return $this->render("enclos/supprimer.html.twig",[
            "enclos"=>$enclos,
            "formulaire"=>$form->createView()
        ]);

    }

}
