<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Enclos;
use App\Entity\Espace;
use App\Form\AnimalSupprimerType;
use App\Form\AnimalType;
use App\Form\EnclosSupprimerType;
use App\Form\EnclosType;
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
        $enclos = $doctrine->getRepository(Enclos::class)->find($id);
        $animaux = $enclos->getAnimaux();
        if (!$enclos) {
            throw $this->createNotFoundException("Aucun enclos avec l'id $id");
        }

        return $this->render('animaux/index.html.twig', [
            'enclos' => $enclos,
            'animaux'=>$doctrine->getRepository(Animal::class)->findAll()
        ]);
    }

    /**
     * @Route("/animaux/modifier/{id}", name="app_animaux_modifier")
     */
    public function modifier($id, ManagerRegistry $doctrine, Request $request): Response
    {
        //créer le formulaire sur le même principe que dans ajouter
        //mais avec une catégorie existante
        $animal = $doctrine->getRepository(Animal::class)->find($id);

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

        return $this->render("animaux/modifier.html.twig", [
            "animal" => $animal,
            "formulaire" => $form->createView()
        ]);
    }

    /**
     * @Route("/animaux/ajouter", name="app_animaux_ajouter")
     */
    public function ajouter(ManagerRegistry $doctrine, Request $request): Response
    {
        $animal = new Animal();

        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $enclosId = $animal->getEnclos()->getId();
            $enclos = $doctrine->getRepository(Enclos::class)->find($enclosId);
            $enclosMaxAnimaux = $enclos->getMaxAnimaux();

            $animaux = $doctrine->getRepository(Animal::class)->findAll();
            $nbAnimauxEnclos = 0;

            foreach ($animaux as $a) {
                if ($a->getEnclos()->getId() == $enclosId) $nbAnimauxEnclos += 1;
                if ($a->getNumeroIdentification() == $animal->getNumeroIdentification()) throw  $this->createNotFoundException("ce numero d'identification appartient à un autre animal !");
            }

            $tailleNID = strlen($animal->getNumero());

            if ($tailleNID == 14 && is_numeric($animal->getNumero())) {
                if ($nbAnimauxEnclos < $enclosMaxAnimaux) {
                    if ($animal->getDepart() == null || $animal->getArrivee() < $animal->getDepart()) {
                        if ($animal->getDdn() == null || $animal->getDdn() < $animal->getArrivee()) {

                            $em = $doctrine->getManager();
                            $em->persist($animal);
                            $em->flush();

                            return $this->redirectToRoute("app_enclos", ["id" => $animal->getEnclos()->getId()]);

                        } else throw $this->createNotFoundException("La date d'arrivée au zoo ne peut pas être antérieur à la date de naissance");
                    } else  throw $this->createNotFoundException("La date de départ ne peut pas être antérieur à la date d'arrivée");
                } else throw $this->createNotFoundException("Le nombre maximum d'animaux a été atteint dans cet enclosLe nombre maximum d'animaux a été atteint dans cet enclos");
            } else throw $this->createNotFoundException("L'ID de l'animal doit faire exactement 14 chiffres et ne doit pas comporter d'autres caractères");
        }

        return $this->render("animaux/ajouter.html.twig", [
            "formulaire" => $form->createView()
        ]);
    }

    /**
     * @Route("/animaux/supprimer/{id}", name="app_animaux_supprimer")
     */
    public function supprimer($id, ManagerRegistry $doctrine, Request $request): Response
    {
        $animal = $doctrine->getRepository(Animal::class)->find($id);

        if (!$animal) {
            throw $this->createNotFoundException("Pas d'enclos avec l'id $id");
        }

        $form = $this->createForm(AnimalSupprimerType::class, $animal);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->remove(($animal));

            $em->flush();

            return $this->redirectToRoute("app_enclos");
        }

        return $this->render("animaux/supprimer.html.twig", [
            "animal" => $animal,
            "formulaire" => $form->createView()
        ]);

    }

}
