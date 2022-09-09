<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Repository\PermissionRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClientController extends AbstractController
{
    #[Route('/client', name: 'app_client')]
    public function index(ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findAll();
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
            'clients'=>$clients,
            'errors'=>null
        ]);
    }

    #[Route('/client/add', name: 'app_client_add')]
    public function add(ManagerRegistry $manager , Request $request,ValidatorInterface $validator): Response
    {
        $client = New Client();
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
    public function showOne(Client $client,ClientRepository $clientRepository,PermissionRepository $permissionRepository): Response
    {
        $permissions=null;
        if ($permissionRepository->finOneJoinClient($client->getId())){
            $permissions =$permissionRepository->finOneJoinClient($client->getId());
        }
        $errors = null;

        $ifClientHavePermission = $permissionRepository->finOneJoinClient([
            'id'=>$client->getId()
        ]);

        if (!$ifClientHavePermission){
            $ifClientHavePermission=null;
        }

        $clientId = $clientRepository->findOneBy([
            'id'=>$client->getId()
        ]);
        return $this->render('client/show_page.html.twig', [
            'client'=>$clientId,
            'permissions'=>$ifClientHavePermission,
            'errors'=>$errors,
            'permissions'=>$permissions
        ]);
    }

    #[Route('/client/{id}/active', name: 'app_client_active')]
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
