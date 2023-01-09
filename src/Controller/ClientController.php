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
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Message;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClientController extends AbstractController
{
    // Page d'accueil
    #[Route('/', name: 'app_home')]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request,ClientRepository $clientRepository): Response
    {
        // Si l'utilisateur a un role READER(Partenaire) il se redirige vers le profil d'un partenaire
        if ($this->getUser()->getRoles()[0] == 'ROLE_READER'){
            // Si l'utilisateur n'est pas active il se redirige vers une page qui montre statu inactive
            if (!$this->getUser()->getClient()->isActive()){
                return $this->redirectToRoute('app_inactive');
            }
            return $this->redirectToRoute('app_guest_client',[
                'id'=>$this->getUser()->getClient()->getId()
            ]);
        }
        // Si l'utilisateur a un role User(Structure) il se redirige vers le profil d'une structure
        if ($this->getUser()->getRoles()[0] == 'ROLE_USER'){
            // Si l'utilisateur n'est pas active ou sont parent (partenaire) n'est pas active il se redirige vers une page qui montre statu inactive
            if (!$this->getUser()->getBranch()->isActive() || !$this->getUser()->getBranch()->isParentStatu()){
                return $this->render('out/inactive.html.twig');
            }
            return $this->redirectToRoute('app_guest_branch',[
                'id'=>$this->getUser()->getBranch()->getId()
            ]);
        }
        $clients = $clientRepository->findAllDesc();
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
            'clients'=>$clients,
            'errors'=>null,
            'param'=>$request->getPathInfo()
        ]);
    }

    // Ajouter un client
    #[Route('/client/add', name: 'app_client_add')]
    #[IsGranted('ROLE_ADMIN')]
    public function add(UserPasswordHasherInterface $hasher,PermissionRepository
    $permissionRepository,ManagerRegistry $manager , Request $request,
                        ValidatorInterface $validator,MailerInterface $mailer,UserRepository $UserRepository): Response
    {
        // On ajoute un nouvel Objet User
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
            // Initialiser des permissions pour le nouveau partenaire
            $addPermissions = New Permission();
            // Récuperer les permissions dont l'id est 1 qu'on va assigner par défault à chaque nouveau partenaire
            $defaultPermissions = $permissionRepository->findOneBy(['id'=>1]);
            // Prendre les permissions dont l'id est 1 et les inserer aux $addPermissions
            $defaultPermissions->cloneClass($addPermissions);
            $addPermissions->setBranch(false);
            // Ajouter les permission à notre data
            $data->addPermission($addPermissions);
            //On ajouter l'adresse email de l'utilisateur
            $userClient->setEmail($data->getTechnicalContact());
            // Vérifier si il y a deja une adresse email dans la base de données
            if ($UserRepository->findOneBy([
                'email'=>$data->getTechnicalContact()
            ]))
            {
                $this->addFlash('alert','Cette adresse email est deja utilisé');
            } else {
                // S'il n'y a pas d'adresse deja utilisé on ajoute un utiliateur avec un mot de passe aleatoire
                $planPassword = md5(uniqid());
                $hashedPassword = $hasher->hashPassword($userClient,$planPassword);
                $userClient->setPassword($hashedPassword);
                $userClient->setConfirmPwd($hashedPassword);
                $userClient->setToken(md5(uniqid()));
                $userClient->setCreateAt(new \DateTime('now'));
                $userClient->setRoles(['ROLE_READER']);
                // On ajoute le client de cet utilisateur qui est le nouveau partenaire inséré dans la DATA
                $userClient->setClient($data);

                $em->persist($userClient);

                // Envoyer un email au compte utilisateur partenaire
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

    // Modifier les informations du compte client
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

            // Envoyer un email au compte utilisateur partenaire pour informer des modifications
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

    // La page qui affiche les informations du client avec les branches
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
            $userBranch->setEmail($data->getManager());
            if ($userRepository->findOneBy([
                'email'=>$userBranch->getEmail()
            ])){
                    $this->addFlash('alert','Cette adresse email est deja utilisée');
            }else {
                $structurePermissions = New Permission();
                $permissions->cloneClass($structurePermissions);
                $structurePermissions->addClient($client);
                $structurePermissions->setBranch(true);
                $token = md5(uniqid());
                $data->setClient($client);
                $data->setToken($token);
                $data->setPermission($structurePermissions);
                $data->setActive(true);
                $data->setCreatedAt(new \DateTime('now'));
                $data->setParentStatu(true);
                $emailClient = (new TemplatedEmail())
                    ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
                    ->to($data->getClient()->getUser()->getEmail())
                    ->subject('Nouvelle Structure')
                    ->context(['token'=>$token])
                    ->htmlTemplate('mails/autorise_branch.html.twig');
                $mailer->send($emailClient);

                $em->persist($data);
                $em->flush();
                $this->addFlash('success','La nouvelle structure a bien été crée en attente de validation');
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

    // Activer ou désactiver un client
    #[Route('/client/{id}/active', name: 'app_client_active')]
    #[IsGranted('ROLE_ADMIN')]
    public function active(Request $request,Client $client,ManagerRegistry $manager
        ,MailerInterface $mailer): Response
    {
        $em = $manager->getManager();
        $status = $client->isActive();
        $branches = $client->getBranches();

        // On change le statu du client on inversant le statu avec le signe "!"
        $client->setActive(!$status);

        // Si le statu du partenaire est active on écrit des messages de désactivation
        if ($status){
            $message = 'Le client '.$client->getName().' a bien été désactivé ';
            $sujet='Désactivation du compte';

            // Notifier les Structures de la désactivation de leurs compte suite à la desactivation du compte parent
            foreach ($branches as $branche){
                $text='Votre compte a été désactivé suite à la désactivation du compte parent';
                $branche->setParentStatu(0);
                $emailClient = (new TemplatedEmail())
                    ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
                    ->to($branche->getManager())
                    ->subject($sujet)
                    ->context(['sujet'=>$sujet,'text'=>$text,'titre'=>$sujet])
                    ->htmlTemplate('mails/activation_desactivation_compte.html.twig');
                $mailer->send($emailClient);
            }
            $text ='Votre compte partenaire est désormais desactivé';
            // Notifier le compte partenaire de la désactivation du compte
            $emailClient = (new TemplatedEmail())
                ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
                ->to($client->getTechnicalContact())
                ->subject($sujet)
                ->context(['sujet'=>$sujet,'text'=>$text,'titre'=>$sujet])
                ->htmlTemplate('mails/activation_desactivation_compte.html.twig');
            $mailer->send($emailClient);
        } else {
            $message = 'Le client '.$client->getName().' a bien été activé';
            $sujet='Activation du compte';
            // Notifier les Structures de l'activation de leurs compte parent
            foreach ($branches as $branche){
                $text='Votre compte parent est activé';
                $branche->setParentStatu(0);
                $emailClient = (new TemplatedEmail())
                    ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
                    ->to($branche->getManager())
                    ->subject($sujet)
                    ->context(['sujet'=>$sujet,'text'=>$text,'titre'=>$sujet])
                    ->htmlTemplate('mails/activation_desactivation_compte.html.twig');
                $mailer->send($emailClient);
            }
            $text='Votre compte est activé';
            $emailClient = (new TemplatedEmail())
                ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
                ->to($client->getTechnicalContact())
                ->subject($sujet)
                ->context(['sujet'=>$sujet,'text'=>$text,'titre'=>$sujet])
                ->htmlTemplate('mails/activation_desactivation_compte.html.twig');
            $mailer->send($emailClient);
        }

        $em->flush();
        $this->addFlash('success',$message);
        
    }

    // Supprimer un client
    #[Route('/client/{id}/delete', name: 'app_client_delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Client $client,ManagerRegistry $manager,MailerInterface $mailer): Response
    {
       $em = $manager->getManager();
       $branches = $client->getBranches();
       $em->remove($client);
       $em->flush();
       $this->addFlash('success','Le partenaire a bien été supprimé');

       // Notifier les structures de ce partenaire de la suppression du leurs compte ainsi que le compte parent
        foreach ($branches as $branche){
            $branche->setParentStatu(0);
            $emailClient = (new TemplatedEmail())
                ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
                ->to($branche->getManager())
                ->subject('Votre compte est supprimé')
                ->htmlTemplate('mails/suppression_compte.html.twig');
            $mailer->send($emailClient);
        }
        // Notifier l'utilisateur du compte partenaire de la suppression de son compte
        $emailClient = (new TemplatedEmail())
            ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
            ->to($client->getTechnicalContact())
            ->subject('Votre compte est supprimé')
            ->htmlTemplate('mails/suppression_compte.html.twig');
        $mailer->send($emailClient);

        return $this->redirectToRoute('app_home');
    }

}
