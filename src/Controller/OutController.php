<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OutController extends AbstractController
{
    #[Route('/out', name: 'app_out')]
    public function index(): Response
    {
        if ($this->getUser()){
            return $this->redirectToRoute('app_home');
        }
        return $this->render('out/index.html.twig', [
            'controller_name' => 'OutController',
        ]);
    }
    #[Route('/inactive', name: 'app_inactive')]
    public function Inactive(): Response
    {
        if ($this->getUser()->getClient() && $this->getUser()->getClient()->isActive()){
            return $this->redirectToRoute('app_home');
        }

        if ($this->getUser()->getBranch() && $this->getUser()->getBranch()->isActive()){
            return $this->redirectToRoute('app_home');
        }

        return $this->render('out/inactive.html.twig', [
        ]);
    }

}
