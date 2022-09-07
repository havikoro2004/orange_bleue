<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Permission;
use App\Form\PermissionType;
use App\Repository\ClientRepository;
use App\Repository\PermissionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PermissionController extends AbstractController
{
    #[Route('/permission', name: 'app_permission')]
    public function index(): Response
    {
        return $this->render('permission/index.html.twig', [
            'controller_name' => 'PermissionController',
        ]);
    }

    #[Route('/permission/add/{id}', name: 'app_permission_add')]

    public function add(Client $client,ManagerRegistry $manager,Request $request ,ValidatorInterface $validator,PermissionRepository $permissionRepository): Response
    {
        $ifClientHavePermission = $permissionRepository->finOneJoinClient([
            'id'=>$client->getId()
        ]);
        if ($ifClientHavePermission){
            return $this->redirectToRoute('app_home');
        }
        $permissions = New Permission();
        $errors = null;
        $em=$manager->getManager();
        $form = $this->createForm(PermissionType::class);
        $data = $form->handleRequest($request);

        $data = $form->getData();
        if ($data){
            $error = $validator->validate($data);
        }
        if ($form->isSubmitted() && $form->isValid()){

            $data->addClient($client);
            $em->persist($data);
            $em->flush();
        }
        return $this->render('permission/add.html.twig', [
            'form'=>$form->createView(),
            'errors'=>$errors
        ]);
    }

    #[Route('/permission/edit/{id}', name: 'app_permission_edit')]
    #[Entity('client', options: ['id' => 'id'])]
    public function edit(): Response
    {
        return $this->render('permission/edit.html.twig', [

        ]);
    }

}
