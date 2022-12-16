<?php

namespace App\Controller;

use App\Entity\Espace;
use App\Form\EspaceType;
use App\Form\EspaceSupprimerType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\throwException;

class EspacesController extends AbstractController
{
    /**
     * @Route("/espaces", name="app_espaces")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(Espace::class);
        $espace = $repo->findAll();

        return $this->render('espaces/index.html.twig', [
            'espace'=>$espace
        ]);
    }

    /**
     * @Route("/espaces/ajouter", name="app_espaces_ajouter")
     */
    public function ajouter(ManagerRegistry $doctrine, Request $request): Response
    {
        $espace= new Espace();
        $form=$this->createForm(EspaceType::class, $espace);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $em->persist(($espace));

            $em->flush();

            return $this->redirectToRoute("app_enclos");
        }

        return $this->render("espaces/ajouter.html.twig",[
            "formulaire"=>$form->createView()
        ]);
    }


    /**
     * @Route("/espaces/modifier/{id}", name="app_espaces_modifier")
     */
    public function modifier($id, ManagerRegistry $doctrine, Request $request): Response
    {
        $espace = $doctrine->getRepository(Espace::class)->find($id);

        if (!$espace){
            throw $this->createNotFoundException("Pas d'espace avec l'id $id");
        }

        $form=$this->createForm(EspaceType::class, $espace);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $em->persist(($espace));

            $em->flush();

            return $this->redirectToRoute("app_esapces");
        }

        return $this->render("espace/modifier.html.twig",[
            "espace"=>$espace,
            "formulaire"=>$form->createView()
        ]);

    }

    /**
     * @Route("/espaces/supprimer/{id}", name="app_espaces_supprimer")
     */
    public function supprimer($id, ManagerRegistry $doctrine, Request $request): Response
    {
        $espace = $doctrine->getRepository(Espace::class)->find($id);

        if (!$espace){
            throw $this->createNotFoundException("Pas d'espace avec l'id $id");
        }

        $form=$this->createForm(EspaceSupprimerType::class, $espace);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em=$doctrine->getManager();
            $em->remove(($espace));

            $em->flush();

            return $this->redirectToRoute("app_espaces");
        }

        return $this->render("espaces/supprimer.html.twig",[
            "espace"=>$espace,
            "formulaire"=>$form->createView()
        ]);
    }

}
