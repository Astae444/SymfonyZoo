<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspacesController extends AbstractController
{
    /**
     * @Route("/espaces", name="app_espaces")
     */
    public function index(): Response
    {
        return $this->render('espaces/index.html.twig', [
            'controller_name' => 'EspacesController',
        ]);
    }
}
