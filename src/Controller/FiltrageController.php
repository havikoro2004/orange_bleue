<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FiltrageController extends AbstractController
{
    #[Route('/find_actifs', name: 'actifs')]
    public function actif(PaginatorInterface $paginator,Request $request,ClientRepository $clientRepository): Response
    {
        if ($request->isXmlHttpRequest()){
            $clients = $paginator->paginate($clientRepository->finActif(),$request->query->getInt('page',1),2);
            return new JsonResponse([
                'ajaxContent'=>$this->renderView('components/client/_ajaxContent.html.twig',[
                    'clients'=>$clients,
                    'errors'=>null,
                    'param'=>$request->getPathInfo()
                ]),
                'paginator'=>$this->renderView('components/client/_pagination.html.twig',[
                    'clients'=>$clients,
                ])
            ]);
        }
        $clients = $paginator->paginate($clientRepository->finActif(),$request->query->getInt('page',1),2);
        return $this->render('client/index.html.twig', [
            'clients'=>$clients,
            'errors'=>null,
            'param'=>$request->getPathInfo()
        ]);
    }

    #[Route('/find_inactif', name: 'inactif')]
    public function inactif(PaginatorInterface $paginator,Request $request,ClientRepository $clientRepository): Response
    {
        if ($request->isXmlHttpRequest()){
            $clients = $paginator->paginate($clientRepository->finInactif(),$request->query->getInt('page',1),2);
            return new JsonResponse([
                'ajaxContent'=>$this->renderView('components/client/_ajaxContent.html.twig',[
                    'clients'=>$clients,
                    'errors'=>null,
                    'param'=>$request->getPathInfo()
                ]),
                'paginator'=>$this->renderView('components/client/_pagination.html.twig',[
                    'clients'=>$clients,
                ])
            ]);
        }
        $clients = $paginator->paginate($clientRepository->finInactif(),$request->query->getInt('page',1),2);
        return $this->render('client/index.html.twig', [
            'clients'=>$clients,
            'errors'=>null,
            'param'=>$request->getPathInfo()
        ]);
    }

    #[Route('/client_letter', name: 'byLetter')]
    public function byLetter(PaginatorInterface $paginator,Request $request,ClientRepository $clientRepository): Response
    {
        $letter = json_decode($request->getContent())->letter;
        $filterStatus = json_decode($request->getContent())->filterStatus;
        $clients = $paginator->paginate($clientRepository->finByLetter($letter,$filterStatus),$request->query->getInt('page',1),2);
        return new JsonResponse([
            'ajaxContent'=>$this->renderView('components/client/_ajaxContent.html.twig',[
                'clients'=>$clients,
                'errors'=>null,
                'param'=>$request->getPathInfo()
            ]),
            'paginator'=>$this->renderView('components/client/_pagination.html.twig',[
                'clients'=>$clients,
            ])
        ]);
    }

}
