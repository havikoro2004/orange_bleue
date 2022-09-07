<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    #[Route('/client/user/add/{id}', name: 'app_user_client_add')]
    #[Entity('client', options: ['id' => 'id'])]
    public function index(UserPasswordHasherInterface $hasher,UserRepository $users ,Client $client,ManagerRegistry $manager,Request $request,ValidatorInterface $validator): Response
    {
        $user = New User();
        $em = $manager->getManager();
        $error = null;
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);
        $data = $form->getData();
        if ($data){
            $error = $validator->validate($data);
        }

        if ($form->isSubmitted() && $form->isValid()){
            if ($form->get('password')->getData() !== $form->get('confirmPwd')->getData()){
                $this->addFlash('alert','Les mots de passe ne correspondent pas');
            } elseif ($users->findOneBy([
                'email'=>$form->get('email')->getData()
            ])){
                $this->addFlash('alert','Cette adresse email est deja utilisé ');
            }else {
                $hachPwd = $hasher->hashPassword($user,$form->get('password')->getData());
                $data->setPassword($hachPwd);
                $data->setConfirmPwd($hachPwd);
                $data->setRoles(['ROLE_READER']);
                $em->persist($data);
                $em->flush();
                $this->addFlash('success','Le nouvel utilisateur a bien été enregistré');
            }

        }

        return $this->render('user/add.html.twig', [
            'form'=>$form->createView(),
            'errors'=>$error,'client'=>$client
        ]);
    }
}
