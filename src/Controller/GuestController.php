<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\BranchRepository;
use App\Repository\PermissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class GuestController extends AbstractController
{
    #[Route('/guest/client/{id}', name: 'app_guest_client')]
    #[IsGranted('ROLE_READER')]
    public function index(Request $request,BranchRepository $branchRepository ,Client $client ,PermissionRepository $permissionRepository): Response
    {

        if ($client != $this->getUser()->getClient()){
            $client = $this->getUser()->getClient();
            $this->redirectToRoute('app_home');
        }
        $branches = $branchRepository->findBranchOfClient($client);
        $permissions = $permissionRepository->finOneJoinClient($client);
        return $this->render('guest/client.html.twig', [
            'errors'=>null,
            'client'=>$client,
            'permissions'=>$permissions,
            'branches'=>$branches
        ]);
    }
}
