<?php

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        if ($this->getUser()->getRoles()[0] == 'ROLE_READER'){
            if (!$this->getUser()->getClient()->isActive()){
                return $this->redirectToRoute('app_inactive');
            }
            return $this->redirectToRoute('app_guest_client',[
                'id'=>$this->getUser()->getClient()->getId()
            ]);
        }
        if ($this->getUser()->getRoles()[0] == 'ROLE_USER'){
            if (!$this->getUser()->getBranch()->isActive()){
                return $this->redirectToRoute('app_inactive');
            }
            return $this->redirectToRoute('app_guest_branch',[
                'id'=>$this->getUser()->getBranch()->getId()
            ]);
        }
        return $this->render('home/index.html.twig');
    }
}
