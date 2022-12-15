<?php

namespace App\Controller;

use App\Entity\Enclos;
use App\Form\EnclosSupprimerType;
use App\Form\EnclosType;
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
        //Aller chercher les catégories dans la base
        //Donc on a besoins d'un repository
        $repo = $doctrine->getRepository(Enclos::class);
        $enclos = $repo->findAll(); //déclancher un select * qui devient une liste de catégorie

        return $this->render('enclos/index.html.twig', [
            'enclos'=>$enclos
        ]);
    }

    /**
     * @Route("/enclos/ajouter", name="app_enclos_ajouter")
     */
    public function ajouter(ManagerRegistry $doctrine, Request $request): Response
    {
        //Création du formulaire avant de le passer à la vue
        //Mais avant il faut créer une catégorie vide
        $enclos= new Enclos();
        //A partir de ça je crée le formulaire
        $form=$this->createForm(EnclosType::class, $enclos);

        //On gère le retour du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //l'objet catégorie est rempli
            //Donc on dit a doctrine de le save dans la Bdd (on utilise un entity manager)
            $em=$doctrine->getManager();
            //et on dit à l'entity manager de mettre la catégorie en question dans la table
            $em->persist(($enclos));

            //on génère l'appel SQL (ici un insert)
            $em->flush();

            return $this->redirectToRoute("app_enclos");
        }

        return $this->render("enclos/ajouter.html.twig",[
            "formulaire"=>$form->createView()
        ]);
    }


    /**
     * @Route("/categorie/modifier/{id}", name="app_categories_modifier")
     */
    public function modifier($id, ManagerRegistry $doctrine, Request $request): Response
    {
        //créer le formulaire sur le même principe que dans ajouter
        //mais avec une catégorie existante
        $enclos = $doctrine->getRepository(Enclos::class)->find($id); // select * from catégorie where id = ...

        //si l'id n'existe pas :
        if (!$enclos){
            throw $this->createNotFoundException("Pas d'enclos avec l'id $id");
        }

        //si l'id existe :
        $form=$this->createForm(EnclosType::class, $enclos);

        //On gère le retour du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //l'objet catégorie est rempli
            //Donc on dit a doctrine de le save dans la Bdd (on utilise un entity manager)
            $em=$doctrine->getManager();
            //et on dit à l'entity manager de mettre la catégorie en question dans la table
            $em->persist(($enclos));

            //on génère l'appel SQL (ici un update)
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
        //créer le formulaire sur le même principe que dans ajouter
        //mais avec une catégorie existante
        $enclos = $doctrine->getRepository(Enclos::class)->find($id); // select * from catégoire where id = ...

        //si l'id n'existe pas :
        if (!$enclos){
            throw $this->createNotFoundException("Pas d'enclos avec l'id $id");
        }

        //si l'id existe :
        $form=$this->createForm(EnclosSupprimerType::class, $enclos);

        //On gère le retour du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //l'objet catégorie est rempli
            //Donc on dit a doctrine de le save dans la Bdd (on utilise un entity manager)
            $em=$doctrine->getManager();
            //et on dit à l'entity manager de mettre la catégorie en question dans la table de supprimer
            $em->remove(($enclos));

            //on génère l'appel SQL (ici un update)
            $em->flush();

            return $this->redirectToRoute("app_enclos");
        }

        return $this->render("enclos/supprimer.html.twig",[
            "enclos"=>$enclos,
            "formulaire"=>$form->createView()
        ]);

    }

}
