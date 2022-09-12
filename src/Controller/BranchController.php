<?php

namespace App\Controller;

use App\Entity\Branch;
use App\Entity\Client;
use App\Form\BranchType;
use App\Repository\BranchRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BranchController extends AbstractController
{
    #[Route('/branch/{id}/edit', name: 'app_branch_edit')]
    public function index(Branch $branch,ManagerRegistry $manager,BranchRepository $branchRepository): Response
    {
        $em = $manager->getManager();
        $branche = $branchRepository->findOneBy([
            'id'=>$branch->getId()
        ]);
        $status = $branche->isActive();
        $message = null;
        $branche->setActive(!$branche->isActive());
        if ($status){
            $message = 'La structure bien été désactivée ';
        } else {
            $message = 'La structure a bien été activée';
        }
        $em->flush();
        $this->addFlash('success',$message);

        return $this->render('branch/index.html.twig', [
            'controller_name' => 'BranchController',
        ]);
    }

    #[Route('/client/{id_client}/branch/{id_branch}/delete', name: 'app_branch_delete')]
    #[Entity('client', options: ['id' => 'id_client'])]
    #[Entity('branch', options: ['id' => 'id_branch'])]
    public function delete(Client $client,Branch $branch,ManagerRegistry $manager): Response
    {
        $em = $manager->getManager();
        $em->remove($branch);
        $em->flush();
        $this->addFlash('success','La structure a bien été supprimée');
        return $this->redirectToRoute('app_client_one',[
            'id'=>$client->getId()
        ]);
    }

    #[Route('/client/{id_client}/branch/{id_branch}/edit', name: 'app_branch_edit')]
    #[Entity('client', options: ['id' => 'id_client'])]
    #[Entity('branch', options: ['id' => 'id_branch'])]
    public function edit(Request $request,ValidatorInterface $validator,Client $client,Branch $branch,ManagerRegistry $manager): Response
    {
        $em = $manager->getManager();
        $error = null;
        $form = $this->createForm(BranchType::class,$branch);
        $form->handleRequest($request);
        $data = $form->getData();
        if ($data){
            $error = $validator->validate($data);
        }
        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($data);
            $em->flush();
            $this->addFlash('success','La structure a bien été modifiée');
        }
        return $this->render('branch/branch_edit.html.twig', [
            'form'=>$form->createView(),
            'errors'=>$error,
            'client'=>$client

        ]);
    }
}
