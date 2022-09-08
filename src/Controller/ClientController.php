<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use App\Repository\PermissionRepository;
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
            $this->addFlash('success','Le profil du client a bien été modifié');
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
            'permissions'=>$ifClientHavePermission
        ]);
    }

}
