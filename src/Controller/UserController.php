<?php

namespace App\Controller;

use App\Entity\Branch;
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
    public function index(UserPasswordHasherInterface $hasher,UserRepository $UserRepository ,Client $client,ManagerRegistry $manager,Request $request,ValidatorInterface $validator): Response
    {
        $users=null;
        $users = $UserRepository->findOneBy([
           'client'=>$client->getId()
        ]);
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
            } elseif ($UserRepository->findOneBy([
                'email'=>$form->get('email')->getData()
            ])){
                $this->addFlash('alert','Cette adresse email est deja utilisé ');
            }else {
                $hachPwd = $hasher->hashPassword($user,$form->get('password')->getData());
                $data->setPassword($hachPwd);
                $data->setConfirmPwd($hachPwd);
                $data->setRoles(['ROLE_READER']);
                $data->setClient($client);
                $data->setCreateAt(new \DateTime('now'));
                $em->persist($data);
                $em->flush();
                $this->addFlash('success','Le nouvel utilisateur a bien été enregistré');
                return $this->redirectToRoute('app_user_client_add',[
                    'id'=>$client->getId()
                ]);
            }

        }

        return $this->render('client/user/add.html.twig', [
            'form'=>$form->createView(),
            'errors'=>$error,'client'=>$client,
            'user'=>$users
        ]);
    }

    #[Route('/client/{id_client}/user/{id_user}/edit')]
    #[Entity('client', options: ['id' => 'id_client'])]
    public function edit(User $user,UserPasswordHasherInterface $hasher,UserRepository $UserRepository ,Client $client,ManagerRegistry $manager,Request $request,ValidatorInterface $validator):Response {
        $em = $manager->getManager();
        $error = null;
        $form= $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        $data = $form->getData($user);
        $userExiste = $UserRepository->findOneBy([
            'email'=>$form->get('email')->getData()
        ]);
        if ($data){
            $error = $validator->validate($data);
        }
        if ($form->isSubmitted() && $form->isValid()){
            if ($form->get('password')->getData() !== $form->get('confirmPwd')->getData()){
                $this->addFlash('alert','Les mots de passe ne correspondent pas');
            } elseif ($userExiste && $userExiste->getId() !== $user->getId()){
                $this->addFlash('alert','Cette adresse email est deja utilisé ');
            }else {
                $hachPwd = $hasher->hashPassword($user,$form->get('password')->getData());
                $data->setPassword($hachPwd);
                $data->setConfirmPwd($hachPwd);
                $data->setRoles(['ROLE_READER']);
                $data->setClient($client);
                $em->persist($data);
                $em->flush();
                $this->addFlash('success','L\'utilisateur a bien été modifié');
            }
        }

        return $this->render('client/user/user_edit.html.twig', [
            'form'=>$form->createView(),
            'errors'=>$error,'client'=>$client,
        ]);
    }

    #[Route('/client/{id_client}/user/{id_user}/delete')]
    #[Entity('client', options: ['id' => 'id_client'])]
    public function delete(Client $client ,ManagerRegistry $manager,User $user):Response {
        $em = $manager->getManager();
        $em->remove($user);
        $em->flush();
        $this-> addFlash('success','L\'utilisateur a bien été supprimé');

        return $this->redirectToRoute('app_user_client_add',[
            'id'=>$client->getId()
        ]);
    }

    #[Route('/client/{id_client}/branch/{id_branch}/add_user', name: 'app_user_branch_add')]
    #[Entity('client',options: ['id'=>'id_client'])]
    #[Entity('branch',options: ['id'=>'id_branch'])]
    public function branchAddUser(UserPasswordHasherInterface $hasher,Branch $branch,ValidatorInterface $validator,UserRepository $UserRepository,Client $client,Request $request,ManagerRegistry $manager):Response {
        $users=null;
        $users = $UserRepository->findOneBy([
            'branch'=>$branch->getId()
        ]);
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
            } elseif ($UserRepository->findOneBy([
                'email'=>$form->get('email')->getData(),
            ])){
                $this->addFlash('alert','Cette adresse email est deja utilisé ');
            }else {
                $hachPwd = $hasher->hashPassword($user,$form->get('password')->getData());
                $data->setPassword($hachPwd);
                $data->setConfirmPwd($hachPwd);
                $data->setRoles(['ROLE_USER']);
                $data->setBranch($branch);
                $data->setCreateAt(new \DateTime('now'));
                $em->persist($data);
                $em->flush();

                $this->addFlash('success','Le nouvel utilisateur a bien été enregistré');
                return $this->redirectToRoute('app_user_branch_add',[
                    'id_client'=>$client->getId(),
                    'id_branch'=>$branch->getId()
                ]);
            }

        }

        return $this->render('branch/user/add.html.twig', [
            'form'=>$form->createView(),
            'client'=>$client,
            'errors'=>$error,
            'user'=>$users,
            'branche'=>$branch

        ]);
    }

    #[Route('/client/{id_client}/branch/{id_branch}/delete_user')]
    #[Entity('client',options: ['id'=>'id_client'])]
    #[Entity('branch',options: ['id'=>'id_branch'])]
    public function deleteUser(Branch $branch,UserRepository $userRepository,Client $client ,ManagerRegistry $manager):Response {
        $user = $userRepository->findOneBy([
            'branch'=>$branch
        ]);
        $em = $manager->getManager();
        $em->remove($user);
        $em->flush();
        $this-> addFlash('success','L\'utilisateur de la structure a bien été supprimé');

        $this->addFlash('success','Le nouvel utilisateur a bien été enregistré');
        return $this->redirectToRoute('app_user_branch_add',[
            'id_client'=>$client->getId(),
            'id_branch'=>$branch->getId()
        ]);
    }



}
