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
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Message;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClientController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    #[IsGranted('ROLE_USER')]
    public function index(PaginatorInterface $paginator,Request $request,ClientRepository $clientRepository): Response
    {
        if ($this->getUser()->getRoles()[0] == 'ROLE_READER'){
            if (!$this->getUser()->getClient()->isActive()){
                return $this->redirectToRoute('app_inactive');
            }
            return $this->redirectToRoute('app_guest_client',[
                'id'=>$this->getUser()->getClient()->getId()
            ]);
        }
        if ($this->getUser()->getRoles()[0] == 'ROLE_USER'){
            if (!$this->getUser()->getBranch()->isActive()){
                return $this->redirectToRoute('app_inactive');
            }
            return $this->redirectToRoute('app_guest_branch',[
                'id'=>$this->getUser()->getBranch()->getId()
            ]);
        }
        if ($request->isXmlHttpRequest()){
            $clients = $paginator->paginate($clientRepository->findAllDesc(),$request->query->getInt('page',1),2);
            return new JsonResponse([
                'ajaxContent'=>$this->renderView('components/client/_ajaxContent.html.twig',[
                    'clients'=>$clients,
                    'errors'=>null,
                    'param'=>$request->getPathInfo()
                ]),
                'paginator'=>$this->renderView('components/client/_pagination.html.twig',[
                    'clients'=>$clients,
                ])
            ]);
        }
        $clients = $paginator->paginate($clientRepository->findAllDesc(),$request->query->getInt('page',1),2);
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
            'clients'=>$clients,
            'errors'=>null,
            'param'=>$request->getPathInfo()
        ]);
    }

    #[Route('/client/add', name: 'app_client_add')]
    #[IsGranted('ROLE_ADMIN')]
    public function add(UserPasswordHasherInterface $hasher,PermissionRepository
    $permissionRepository,ManagerRegistry $manager , Request $request,
                        ValidatorInterface $validator,MailerInterface $mailer,UserRepository $UserRepository): Response
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
            if ($UserRepository->findOneBy([
                'email'=>$data->getTechnicalContact()
            ]))
            {
                $this->addFlash('alert','Cette adresse email est deja utilisé');
            } else {
                $planPassword = md5(uniqid());
                $hashedPassword = $hasher->hashPassword($userClient,$planPassword);
                $userClient->setPassword($hashedPassword);
                $userClient->setConfirmPwd($hashedPassword);
                $userClient->setToken(md5(uniqid()));
                $userClient->setCreateAt(new \DateTime('now'));
                $userClient->setRoles(['ROLE_READER']);
                $userClient->setClient($data);
                $em->persist($userClient);

                $email = (new TemplatedEmail())
                    ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
                    ->to($userClient->getEmail())
                    ->subject('Activation de compte')
                    ->context(['token'=>$userClient->getToken()])
                    ->htmlTemplate('mails/activation.html.twig');
                $mailer->send($email);


                $em->persist($data);
                $em->flush();
                $this->addFlash('success','Le nouveau partenaire a bien été ajouté');
                return $this->redirectToRoute('app_home');

            }

        }

        return $this->render('client/add.html.twig', [
            'form'=>$form->createView(),
            'errors'=>$error
        ]);
    }


    #[Route('/client/{id}/edit', name: 'app_client_edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(UserRepository $userRepository,Client $client,
                         ManagerRegistry $manager , Request $request,
                         ValidatorInterface $validator,MailerInterface $mailer): Response
    {
        $userClient= $userRepository->findOneBy([
            'client'=>$client
        ]);

        $error =null;
        $em = $manager->getManager();
        $form = $this->createForm(ClientType::class,$client);
        $form->handleRequest($request);

        $data = $form->getData();
        if ($data){
            $error = $validator->validate($data);
        }
        if ($form->isSubmitted() && $form->isValid()){

            $email = (new TemplatedEmail())
                ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
                ->to($userClient->getEmail())
                ->subject('Modification du profil')
                ->context(['sujet'=>'Votre profil a été modifié connectez-vous pour voir plus de détails'])
                ->htmlTemplate('mails/email_notifications.html.twig');
            $mailer->send($email);

            $em->persist($data);
            $em->flush();
            $this->addFlash('success','Le profil du client '.$client->getName().' a bien été modifié');
            return $this->redirectToRoute('app_home');
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
                            ,ManagerRegistry $manager,UserRepository $userRepository,
                            Request $request,Client $client,ClientRepository $clientRepository,
                            UserPasswordHasherInterface $hasher,
                            PermissionRepository $permissionRepository,MailerInterface $mailer): Response
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
            if ($userRepository->findOneBy([
                'email'=>$userBranch->getEmail()
            ])){
                    $this->addFlash('alert','Cette adresse email est deja utilisée');
            }else {
                $planPassword = md5(uniqid());
                $hashedPassword = $hasher->hashPassword($userBranch,$planPassword);
                $userBranch->setPassword($hashedPassword);
                $userBranch->setConfirmPwd($hashedPassword);
                $userBranch->setCreateAt(new \DateTime('now'));
                $userBranch->setRoles(['ROLE_USER']);
                $userBranch->setBranch($data);
                $userBranch->setToken(md5(uniqid()));

                $email = (new TemplatedEmail())
                    ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
                    ->to($userBranch->getEmail())
                    ->subject('Activation de compte')
                    ->context(['token'=>$userBranch->getToken()])
                    ->htmlTemplate('mails/activation.html.twig');
                $mailer->send($email);

                $emailClient = (new TemplatedEmail())
                    ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
                    ->to($data->getClient()->getUser()->getEmail())
                    ->subject('Nouvelle Structure')
                    ->context(['sujet'=>'Une nouvelle structure a été crée sur votre profil'])
                    ->htmlTemplate('mails/new_user.html.twig');
                $mailer->send($emailClient);

                $em->persist($userBranch);

                $em->persist($data);
                $em->flush();
                $this->addFlash('success','La nouvelle structure a bien été crée');
                return $this->redirect($request->getUri());
            }

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
    public function active(Client $client,ManagerRegistry $manager
        ,MailerInterface $mailer): Response
    {
        $em = $manager->getManager();
        $status = $client->isActive();
        if ($status){
            $message = 'Le client '.$client->getName().'a bien été désactivé ';
            $sujet='Désactivation du compte';
            $text ='Votre compte a été désactivé';
            $branches = $client->getBranches();
            foreach ($branches as $branche){
                $text='Votre compte a été désactivé suite à la désactivation du compte parent';
                $branche->setActive(0);
                $emailClient = (new TemplatedEmail())
                    ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
                    ->to($branche->getManager())
                    ->subject($sujet)
                    ->context(['sujet'=>$sujet,'text'=>$text,'titre'=>$sujet])
                    ->htmlTemplate('mails/activation_desactivation_compte.html.twig');
                $mailer->send($emailClient);
            }

        } else {
            $message = 'Le client '.$client->getName().' a bien été activé';
            $sujet='Activation du compte';
            $text ='Votre compte partenaire est désormais activé';
        }
        $client->setActive(!$client->isActive());

        $emailClient = (new TemplatedEmail())
            ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
            ->to($client->getTechnicalContact())
            ->subject($sujet)
            ->context(['sujet'=>$sujet,'text'=>$text,'titre'=>$sujet])
            ->htmlTemplate('mails/activation_desactivation_compte.html.twig');
        $mailer->send($emailClient);

        $em->flush();
        $this->addFlash('success',$message);
        return $this->redirectToRoute('app_client');
    }

    #[Route('/client/{id}/delete', name: 'app_client_delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Client $client,ManagerRegistry $manager,MailerInterface $mailer): Response
    {
       $em = $manager->getManager();
       $em->remove($client);
       $em->flush();
       $this->addFlash('success','Le partenaire a bien été supprimé');

        $emailClient = (new TemplatedEmail())
            ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
            ->to($client->getTechnicalContact())
            ->subject('Votre compte est supprimé vous ne pouvez plus y acceder')
            ->htmlTemplate('mails/suppression_compte.html.twig');
        $mailer->send($emailClient);


        return $this->redirectToRoute('app_home');
    }

}
