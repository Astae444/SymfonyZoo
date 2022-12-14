<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimauxController extends AbstractController
{
    /**
     * @Route("/animaux", name="app_animaux")
     */
    public function index(): Response
    {
        return $this->render('animaux/index.html.twig', [
            'controller_name' => 'AnimauxController',
        ]);
    }
}
