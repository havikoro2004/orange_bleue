<?php

namespace App\Controller;

use App\Entity\Branch;
use App\Entity\Client;
use App\Repository\BranchRepository;
use App\Repository\PermissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class GuestController extends AbstractController
{
    // Page profil partenaire en lécture seul
    #[Route('/guest/client/{id}', name: 'app_guest_client')]
    #[IsGranted('ROLE_READER')]
    public function index(BranchRepository $branchRepository ,Client $client ,PermissionRepository $permissionRepository): Response
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

    // Page profil d'une structure en lécture seul
    #[Route('/guest/branch/{id}', name: 'app_guest_branch')]
    #[IsGranted('ROLE_USER')]
    public function branchIndex(Branch $branch): Response
    {
        $permissions =$branch->getPermission();
        if ($branch != $this->getUser()->getBranch()){
            $branch = $this->getUser()->getBranch();
            $this->redirectToRoute('app_home');
        }
        return $this->render('guest/branch.html.twig', [
            'permissions'=>$permissions,
            'branche'=>$branch

        ]);
    }

}
