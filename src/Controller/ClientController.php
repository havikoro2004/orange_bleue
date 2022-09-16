<?php

namespace App\Controller;

use App\Entity\Branch;
use App\Entity\Client;
use App\Entity\Permission;
use App\Entity\User;
use App\Form\BranchType;
use App\Form\ClientType;
use App\Repository\BranchRepository;
use App\Repository\ClientRepository;
use App\Repository\PermissionRepository;
use App\Repository\UserRepository;

use App\Services\CloneClass;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClientController extends AbstractController
{
    #[Route('/client', name: 'app_client')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findAllDesc();
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
            'clients'=>$clients,
            'errors'=>null
        ]);
    }

    #[Route('/client/add', name: 'app_client_add')]
    #[IsGranted('ROLE_ADMIN')]
    public function add(UserPasswordHasherInterface $hasher,PermissionRepository $permissionRepository,ManagerRegistry $manager , Request $request,ValidatorInterface $validator): Response
    {
        $userClient = New User();
        $error =null;
        $em = $manager->getManager();
        $form = $this->createForm(ClientType::class);
        $form->handleRequest($request);

        $data = $form->getData();
        if ($data){
            $error = $validator->validate($data);
        }
        if ($form->isSubmitted() && $form->isValid()){
            $data->setCreateAt(new \DateTime('now'));
            $data->setActive(true);
            $addPermissions = New Permission();
            $defaultPermissions = $permissionRepository->findOneBy(['id'=>1]);
            $defaultPermissions->cloneClass($addPermissions);
            $addPermissions->setBranch(false);
            $data->addPermission($addPermissions);


            $userClient->setEmail($data->getTechnicalContact());
            $planPassword = md5(uniqid());
            $hashedPassword = $hasher->hashPassword($userClient,$planPassword);
            $userClient->setPassword($hashedPassword);
            $userClient->setConfirmPwd($hashedPassword);
            $userClient->setCreateAt(new \DateTime('now'));
            $userClient->setRoles(['ROLE_READER']);
            $userClient->setClient($data);
            $em->persist($userClient);

            $em->persist($data);
            $em->flush();
            $this->addFlash('success','Le nouveau partenaire a bien été ajouté');
            return $this->redirectToRoute('app_client');
        }

        return $this->render('client/add.html.twig', [
            'form'=>$form->createView(),
            'errors'=>$error
        ]);
    }


    #[Route('/client/{id}/edit', name: 'app_client_edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Client $client,ManagerRegistry $manager , Request $request,ValidatorInterface $validator): Response
    {
        $error =null;
        $em = $manager->getManager();
        $form = $this->createForm(ClientType::class,$client);
        $form->handleRequest($request);

        $data = $form->getData();
        if ($data){
            $error = $validator->validate($data);
        }
        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($data);
            $em->flush();
            $this->addFlash('success','Le profil du client '.$client->getName().' a bien été modifié');
            return $this->redirectToRoute('app_client');
        }

        return $this->render('client/client_edit.html.twig', [
            'form'=>$form->createView(),
            'errors'=>$error
        ]);
    }

    #[Route('/client/{id}', name: 'app_client_one')]
    #[Entity('client', options: ['id' => 'id'])]
    #[IsGranted('ROLE_ADMIN')]
    public function showOne(BranchRepository $branchRepository,ValidatorInterface $validator
                            ,ManagerRegistry $manager,
                            Request $request,Client $client,ClientRepository $clientRepository,
                            UserPasswordHasherInterface $hasher,
                            PermissionRepository $permissionRepository): Response
    {
        $errors = null;
        $permissions=null;
        $branches=null;

        if ($permissionRepository->finOneJoinClient($client->getId())){
            $permissions =$permissionRepository->finOneJoinClient($client->getId());
        }
        $ifClientHavePermission = $permissionRepository->finOneJoinClient([
            'id'=>$client->getId()
        ]);

        if (!$ifClientHavePermission){
            $ifClientHavePermission=null;
        }

        $clientId = $clientRepository->findOneBy([
            'id'=>$client->getId()
        ]);

        $branch = New Branch();
        $em = $manager->getManager();
        $form = $this->createForm(BranchType::class);
        $form->handleRequest($request);
        $data = $form->getData($branch);
        if ($data){
            $error = $validator->validate($data);
        }
        if ($form->isSubmitted() && $form->isValid()){
            $userBranch = New User();
            $structurePermissions = New Permission();
            $permissions->cloneClass($structurePermissions);
            $structurePermissions->addClient($client);
            $structurePermissions->setBranch(true);

            $data->setClient($client);
            $data->setPermission($structurePermissions);
            $data->setActive(true);
            $data->setCreatedAt(new \DateTime('now'));

            $userBranch->setEmail($data->getManager());
            $planPassword = md5(uniqid());
            $hashedPassword = $hasher->hashPassword($userBranch,$planPassword);
            $userBranch->setPassword($hashedPassword);
            $userBranch->setConfirmPwd($hashedPassword);
            $userBranch->setCreateAt(new \DateTime('now'));
            $userBranch->setRoles(['ROLE_USER']);
            $userBranch->setBranch($data);
            $em->persist($userBranch);

            $em->persist($data);
            $em->flush();
            $this->addFlash('success','La nouvelle structure a bien été crée');
            return $this->redirect($request->getUri());

        }

        /*  Afficher les branches du partenaire  */

        $branches = $branchRepository->findBranchOfClient($client);

        return $this->render('client/show_page.html.twig', [
            'client'=>$clientId,
            'permissions'=>$ifClientHavePermission,
            'errors'=>$errors,
            'permissions'=>$permissions,
            'branches'=>$branches,
            'form'=>$form->createView()
        ]);
    }

    #[Route('/client/{id}/active', name: 'app_client_active')]
    #[IsGranted('ROLE_ADMIN')]
    public function active(Client $client,ManagerRegistry $manager , Request $request,ValidatorInterface $validator): Response
    {
        $em = $manager->getManager();
        $status = $client->isActive();
        $message = null;
        $error=null;
        if ($status){
            $message = 'Le client '.$client->getName().'a bien été désactivé ';
        } else {
            $message = 'Le client '.$client->getName().' a bien été activé';
        }
        $client->setActive(!$client->isActive());
        $em->flush();
        $this->addFlash('success',$message);
        return $this->redirectToRoute('app_client');
    }

    #[Route('/client/{id}/delete', name: 'app_client_delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(UserRepository $userRepository,Client $client,ManagerRegistry $manager): Response
    {
        $em = $manager->getManager();
        $user = $userRepository->findOneBy([
           'client'=>$client->getId()
        ]);
       if ($user){
           $em->remove($user);
           $em->flush();
       }

       $em->remove($client);
       $em->flush();
       $this->addFlash('success','Le partenaire a bien été supprimé');
       return $this->redirectToRoute('app_client');
    }

}
