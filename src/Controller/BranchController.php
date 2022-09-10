<?php

namespace App\Controller;

use App\Form\BranchType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BranchController extends AbstractController
{
    #[Route('/branch', name: 'app_branch')]
    public function index(): Response
    {
        return $this->render('branch/index.html.twig', [
            'controller_name' => 'BranchController',
        ]);
    }
}
