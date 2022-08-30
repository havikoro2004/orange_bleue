<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TesteController extends AbstractController
{
    #[Route('/teste', name: 'app_teste')]
    #[Grantes]
    public function index(): Response
    {

        return $this->render('teste/index.html.twig', [
            'controller_name' => 'TesteController',
        ]);
    }
}
