<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/client', name: 'app_client')]
    public function index(): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

    #[Route('/client/add', name: 'app_clien_add')]
    public function add(ManagerRegistry $manager , Request $request): Response
    {
        $client = New Client();
        $em = $manager->getManager();
        $form = $this->createForm(ClientType::class);
        $form->handleRequest($request);

        return $this->render('client/add.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
