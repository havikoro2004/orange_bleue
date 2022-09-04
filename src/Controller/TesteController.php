<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class TesteController extends AbstractController
{
    #[Route('/teste', name: 'app_teste')]
    public function index(ManagerRegistry $manager,Request $request,UserPasswordHasherInterface $hacher): Response
    {
//        $user = New User();
//        $planPwd = '102030mp3';
//        $passHached = $hacher->hashPassword($user,$planPwd);
//        $user->setPassword($passHached);
//        $user->setEmail('havikoro2004@gmail.com');
//        $user->setRoles(['ROLE_ADMIN']);
//        $em = $manager->getManager();
//        $em->persist($user);
//        $em->flush();
        return $this->render('teste/index.html.twig', [
            'controller_name' => 'TesteController',
        ]);
    }
}
