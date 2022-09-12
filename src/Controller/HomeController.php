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
            return $this->redirectToRoute('app_guest_client',[
                'id'=>$this->getUser()->getClient()->getId()
            ]);
        }
        return $this->render('home/index.html.twig');
    }
}
