<?php

namespace App\Controller;
use App\Entity\Branch;
use App\Entity\Client;
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
    // Modifier les permissions d'un client partenaire
    #[Route('/permission/edit/{id}', name: 'app_permission_edit')]
    #[Entity('client', options: ['id' => 'id'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request,Client $client,ManagerRegistry $manager
        ,PermissionRepository $permissionRepository,MailerInterface $mailer): Response
    {
        $em = $manager->getManager();
        // Récupérer l'id des permissions de la branchee
        $permissionsByClient = $permissionRepository->finOneJoinClient($client);


        // Créer un objet pour avoir la methode getMethodes() qui active ou désactive une permission selon son id
        $getMethode = New getPermissionsMethodes();

        // Récupérer l'input de la permission
        $axiosPost = json_decode($request->getContent())->inputName;

        // Appliquer la modification de la permission avec la methode de l'objet getPermissionsMethodes
        $getMethode->getMethodes($permissionsByClient,$axiosPost);

        $em->flush();
        $this->addFlash('success','La permission a bien été changée');

        // Envoyer un email pour notifier les changement des permissions
        $emailClient = (new TemplatedEmail())
            ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
            ->to($client->getTechnicalContact())
            ->subject('Modification des permissions')
            ->context(['text'=>'Une ou plusieurs changement ont été effectués sur vos permissions'])
            ->htmlTemplate('mails/modif_permissions.html.twig');
        $mailer->send($emailClient);

    }

    // Modifier les permissions d'une structure
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
        // Récupérer l'id des permissions de la branchee
        $permissions = $permissionRepository->fineOneJoinBranch($branches);

        // Créer un objet pour avoir la methode getMethodes() qui active ou désactive une permission selon son id
        $getMethode = New getPermissionsMethodes();

        // Récupérer l'input de la permission
        $axiosPost = json_decode($request->getContent())->inputName;

        // Appliquer la modification de la permission avec la methode de l'objet getPermissionsMethodes
        $getMethode->getMethodes($permissions,$axiosPost);
        $em->flush();

        // Envoyer un email pour notifier les changement des permissions
        $emailClient = (new TemplatedEmail())
            ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
            ->to($branches->getManager(),$branches->getClient()->getTechnicalContact())
            ->subject('Modification des permissions')
            ->context(['text'=>'Modification de la structure dont l\'id est :'.$branches->getId()])
            ->htmlTemplate('mails/modif_permissions.html.twig');
        $mailer->send($emailClient);

    }

}
