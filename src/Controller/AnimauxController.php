<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Enclos;
use App\Entity\Espace;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimauxController extends AbstractController
{
    /**
     * @Route("/animaux/{id}", name="app_animaux")
     */
    public function index($id, ManagerRegistry $doctrine): Response
    {
        //Aller chercher les catégories dans la base
        //Donc on a besoins d'un repository
        $enclos = $doctrine->getRepository(Enclos::class)->find($id);
        //si on n'a rien trouvé -> 404
        if (!$enclos) {
            throw $this->createNotFoundException("Aucune catégorie avec l'id $id");
        }

        return $this->render('animaux/index.html.twig', [
            'enclos' => $enclos,
            'animaux' => $enclos->getAnimaux(),

        ]);
    }

    /**
     * @Route("/animaux/modifier/{id}", name="app_animaux_modifier")
     */
    public function modifier($id, ManagerRegistry $doctrine, Request $request): Response
    {
        //créer le formulaire sur le même principe que dans ajouter
        //mais avec une catégorie existante
        $animaux = $doctrine->getRepository(Animal::class)->find($id); // select * from catégorie where id = ...

        //si l'id n'existe pas :
        if (!$animal) {
            throw $this->createNotFoundException("Pas d'animal avec l'id $id");
        }

        //si l'id existe :
        $form = $this->createForm(AnimalType::class, $animal);

        //On gère le retour du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //l'objet catégorie est rempli
            //Donc on dit a doctrine de le save dans la Bdd (on utilise un entity manager)
            $em = $doctrine->getManager();
            //et on dit à l'entity manager de mettre la catégorie en question dans la table
            $em->persist(($animal));

            //on génère l'appel SQL (ici un update)
            $em->flush();

            return $this->redirectToRoute("app_enclos");
        }

        return $this->render("animaux/modifier.html.twig",[
            "animal"=>$animal,
            "formulaire"=>$form->createView()
        ]);
    }

    /**
     * @Route("/animaux/ajouter", name="app_animaux_ajouter")
     */
    public function ajouter(ManagerRegistry $doctrine, Request $request): Response
    {
        //Création du formulaire avant de le passer à la vue
        //Mais avant il faut créer une catégorie vide
        $animal= new Animal();
        //A partir de ça je crée le formulaire
        $form=$this->createForm(AnimalType::class, $animal);

        //On gère le retour du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //l'objet catégorie est rempli
            //Donc on dit a doctrine de le save dans la Bdd (on utilise un entity manager)
            $em=$doctrine->getManager();
            //et on dit à l'entity manager de mettre la catégorie en question dans la table
            $em->persist(($animal));

            //on génère l'appel SQL (ici un insert)
            $em->flush();

            return $this->redirectToRoute("app_enclos");
        }

        return $this->render("animaux/ajouter.html.twig",[
            "formulaire"=>$form->createView()
        ]);
    }

    /**
     * @Route("/animaux/supprimer/{id}", name="app_animaux_supprimer")
     */
    public function supprimer($id, ManagerRegistry $doctrine, Request $request): Response
    {
        //créer le formulaire sur le même principe que dans ajouter
        //mais avec une catégorie existante
        $animal = $doctrine->getRepository(Animal::class)->find($id); // select * from catégorie where id = ...

        //si l'id n'existe pas :
        if (!$animal){
            throw $this->createNotFoundException("Pas d'enclos avec l'id $id");
        }

        //si l'id existe :
        $form=$this->createForm(AnimalSupprimerType::class, $animal);

        //On gère le retour du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //l'objet catégorie est rempli
            //Donc on dit a doctrine de le save dans la Bdd (on utilise un entity manager)
            $em=$doctrine->getManager();
            //et on dit à l'entity manager de mettre la catégorie en question dans la table de supprimer
            $em->remove(($animal));

            //on génère l'appel SQL (ici un delete)
            $em->flush();

            return $this->redirectToRoute("app_enclos");
        }

        return $this->render("animaux/supprimer.html.twig",[
            "animal"=>$animal,
            "formulaire"=>$form->createView()
        ]);

    }

}
