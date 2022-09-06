<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function add(ManagerRegistry $manager , Request $request,ValidatorInterface $validator): Response
    {
        $error =null;
        $client = New Client();
        $em = $manager->getManager();
        $form = $this->createForm(ClientType::class);
        $form->handleRequest($request);

        $data = $form->getData($client);
        if ($data){
            $error = $validator->validate($data);
        }
        if ($form->isSubmitted() && $form->isValid()){
            dd($data);
        }

        return $this->render('client/add.html.twig', [
            'form'=>$form->createView(),
            'errors'=>$error
        ]);
    }
}
