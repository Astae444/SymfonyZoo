<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EnclosController extends AbstractController
{
    /**
     * @Route("/enclos", name="app_enclos")
     */
    public function index(): Response
    {
        return $this->render('enclos/index.html.twig', [
            'controller_name' => 'EnclosController',
        ]);
    }
}
