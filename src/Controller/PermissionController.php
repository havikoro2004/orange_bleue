<?php

namespace App\Controller;
use App\Entity\Branch;
use App\Entity\Client;
use App\Entity\Permission;
use App\Form\PermissionType;
use App\Repository\BranchRepository;
use App\Repository\PermissionRepository;
use App\Services\getPermissionsMethodes;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Message;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PermissionController extends AbstractController
{
    #[Route('/permission', name: 'app_permission')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('permission/index.html.twig', [
            'controller_name' => 'PermissionController',
        ]);
    }


    #[Route('/permission/edit/{id}', name: 'app_permission_edit')]
    #[Entity('client', options: ['id' => 'id'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request,Client $client,ManagerRegistry $manager
        ,PermissionRepository $permissionRepository,MailerInterface $mailer): Response
    {
        $em = $manager->getManager();
        $permissionsByClient = $permissionRepository->finOneJoinClient($client);
        $getMethode = New getPermissionsMethodes();
        $axiosPost = json_decode($request->getContent())->inputName;
        $getMethode->getMethodes($permissionsByClient,$axiosPost);
        $em->flush();
        $this->addFlash('success','La permission a bien été changée');

        $emailClient = (new TemplatedEmail())
            ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
            ->to($client->getTechnicalContact())
            ->subject('Modification des permissions')
            ->context(['text'=>'Une ou plusieurs changement ont été effectués sur vos permissions'])
            ->htmlTemplate('mails/modif_permissions.html.twig');
        $mailer->send($emailClient);

    }

    #[Route('/permission/{clien_id}/{branch_id}/edit', name: 'app_permission_branch_edit')]
    #[Entity('client', options: ['id' => 'clien_id'])]
    #[Entity('branch', options: ['id' => 'branch_id'])]
    #[IsGranted('ROLE_ADMIN')]
    public function branchEditPermissions(BranchRepository $branchRepository,
                                          Request $request,Branch $branch,
                                          ManagerRegistry $manager,
                                          PermissionRepository $permissionRepository,MailerInterface $mailer): Response
    {
        $em = $manager->getManager();
        $branches = $branchRepository->findOneBy([
            'id'=>$branch->getId()
        ]);
        $permissions = $permissionRepository->fineOneJoinBranch($branches);
        $getMethode = New getPermissionsMethodes();
        $axiosPost = json_decode($request->getContent())->inputName;
        $getMethode->getMethodes($permissions,$axiosPost);
        $em->flush();

        $emailClient = (new TemplatedEmail())
            ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
            ->to($branches->getManager(),$branches->getClient()->getTechnicalContact())
            ->subject('Modification des permissions')
            ->context(['text'=>'Modification de la structure dont l\'id est :'.$branches->getId()])
            ->htmlTemplate('mails/modif_permissions.html.twig');
        $mailer->send($emailClient);

    }

}
