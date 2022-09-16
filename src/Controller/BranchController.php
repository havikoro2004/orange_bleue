<?php

namespace App\Controller;

use App\Entity\Branch;
use App\Entity\Client;
use App\Form\BranchType;
use App\Repository\BranchRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BranchController extends AbstractController
{
    #[Route('/branch/{id}/edit', name: 'app_branch_active')]
    #[IsGranted('ROLE_ADMIN')]
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
    #[IsGranted('ROLE_ADMIN')]
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
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request,ValidatorInterface $validator,
                         Client $client,Branch $branch,
                         ManagerRegistry $manager,UserRepository $userRepository,
                         MailerInterface $mailer): Response
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
            $userClient = $userRepository->findOneBy([
                'branch'=>$branch
            ]);
            $email = (new TemplatedEmail())
                ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
                ->to($userClient->getEmail(),'havikoro2004@gmail.com')
                ->subject('Modification du profil')
                ->context(['sujet'=>'Votre structure a été modifié connectez-vous pour voir plus de détails'])
                ->htmlTemplate('mails/email_notifications.html.twig');
            $mailer->send($email);

            $em->persist($data);
            $em->flush();
            $this->addFlash('success','La structure a bien été modifiée');

            return $this->redirectToRoute('app_home');
        }
        return $this->render('branch/branch_edit.html.twig', [
            'form'=>$form->createView(),
            'errors'=>$error,
            'client'=>$client

        ]);
    }
}
