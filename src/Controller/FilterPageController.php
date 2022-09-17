<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilterPageController extends AbstractController
{
    #[Route('/clients', name: 'clients')]
    public function index(Request $request,ClientRepository $clientRepository): Response
    {

        if ($request->isXmlHttpRequest()){
            $clients =$clientRepository->findAllDesc();
            return new JsonResponse([
                'content'=>$this->renderView('client/index.html.twig',[
                    'clients'=>$clients,
                    'errors'=>null
                ]),
            ]);
        }

        $clients = $clientRepository->findAllDesc();

        return  $this->render('client/index.html.twig',[
            'clients'=>$clients,
            'errors'=>null
        ]);
    }
}
