<?php

namespace App\Controller;

use App\Entity\Branch;
use App\Repository\BranchRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BranchController extends AbstractController
{
    #[Route('/branch/{id}/edit', name: 'app_branch_edit')]
    public function index(Branch $branch,ManagerRegistry $manager,BranchRepository $branchRepository): Response
    {
        $em = $manager->getManager();
        $branche = $branchRepository->findOneBy([
            'id'=>$branch->getId()
        ]);
        $status = $branche->isActive();
        $message = null;
        $branche->setActive(!$branche->isActive());
        if ($status){
            $message = 'La structure bien été désactivée ';
        } else {
            $message = 'La structure a bien été activée';
        }
        $em->flush();
        $this->addFlash('success',$message);

        return $this->render('branch/index.html.twig', [
            'controller_name' => 'BranchController',
        ]);
    }
}
