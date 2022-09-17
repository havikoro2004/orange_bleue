<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ActivationCompteController extends AbstractController
{
    #[Route('/activation/{token}', name: 'app_activation_compte')]
    public function index($token,UserRepository $userRepository,
                          UserPasswordHasherInterface $hasher,Request $request,
                          ManagerRegistry $managerRegistry): Response
    {
        $user = $userRepository->findOneBy([
           'token'=>$token
        ]);
        if (!$user){
            throw $this->createNotFoundException();
        }
        $form = $this->createForm(ChangePasswordType::class);
        $em = $managerRegistry->getManager();
        $data =$form->handleRequest($request);

        if ($form->isSubmitted()){
            if ($data->get('password')->getViewData() !== $data->get('confirm_pass')->getViewData()){
                dd($data->get('password')->getViewData());
                $this->addFlash('alert','Les mots de passe ne correspondent pas');
            } else {

                $planPassword = $data->get('password')->getViewData();
                $hachedPassword = $hasher->hashPassword($user,$planPassword);
                $user->setPassword($hachedPassword);
                $user->setConfirmPwd($hachedPassword);
                $user->setToken(null);

                $em->flush();

                $this->addFlash('success','Félicitation votre compte est désormais actif');
                return $this->redirectToRoute('app_login');

            }
        }
        return $this->render('activation_compte/index.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
