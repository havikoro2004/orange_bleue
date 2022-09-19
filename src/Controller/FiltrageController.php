<?php

namespace App\Controller;

use App\Repository\BranchRepository;
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

    #[Route('/branch_actifs', name: 'branch_actif')]
    public function branchActifs(ClientRepository $clientRepository,Request $request,BranchRepository $branchRepository): Response
    {
    //Je n'utilise pas le if $request->isXttp ... si je n'ai pas de pagination
        $idClient = intval(json_decode($request->getContent())->idClient);
        $client = $clientRepository->findOneBy([
            'id'=>$idClient
        ]);
        $branche = $branchRepository->findActif($client);
            return new JsonResponse([
                'branchCard'=>$this->renderView('components/branch/_ajaxBranchContent.html.twig',[
                    'branches'=>$branche,
                    'errors'=>null,
                    'client'=>$client,
                    'param'=>$request->getPathInfo()
                ]),
            ]);
        }

    #[Route('/branch_inactifs', name: 'branch_inactif')]
    public function branchInactifs(ClientRepository $clientRepository,Request $request,BranchRepository $branchRepository): Response
    {
        //Je n'utilise pas le if $request->isXttp ... si je n'ai pas de pagination
        $idClient = intval(json_decode($request->getContent())->idClient);
        $client = $clientRepository->findOneBy([
            'id'=>$idClient
        ]);
        $branche = $branchRepository->findInactif($client);
        return new JsonResponse([
            'branchCard'=>$this->renderView('components/branch/_ajaxBranchContent.html.twig',[
                'branches'=>$branche,
                'errors'=>null,
                'client'=>$client,
                'param'=>$request->getPathInfo()
            ]),
        ]);
    }

    #[Route('/branch_tous', name: 'branch_tous')]
    public function branchTous(ClientRepository $clientRepository,Request $request,BranchRepository $branchRepository): Response
    {
        //Je n'utilise pas le if $request->isXttp ... si je n'ai pas de pagination
        $idClient = intval(json_decode($request->getContent())->idClient);
        $client = $clientRepository->findOneBy([
            'id'=>$idClient
        ]);
        $branche = $branchRepository->findAllTou($client);
        return new JsonResponse([
            'branchCard'=>$this->renderView('components/branch/_ajaxBranchContent.html.twig',[
                'branches'=>$branche,
                'errors'=>null,
                'client'=>$client,
                'param'=>$request->getPathInfo()
            ]),
        ]);
    }
}
