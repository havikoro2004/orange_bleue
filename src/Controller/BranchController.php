<?php

namespace App\Controller;
use App\Entity\Branch;
use App\Entity\Client;
use App\Entity\User;
use App\Form\BranchType;
use App\Repository\BranchRepository;
use App\Repository\ClientRepository;
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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Vich\UploaderBundle\Exception\NoFileFoundException;

class BranchController extends AbstractController
{
    // Activer ou désactiver une branch en récupérant son id
    #[Route('/branch/{id}/edit', name: 'app_branch_active')]
    #[Entity('branch', options: ['id' => 'id'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Branch $branch,ManagerRegistry $manager,
                          MailerInterface $mailer): Response
    {
        $em = $manager->getManager();
        $status = $branch->isActive();
        $branch->setActive(!$branch->isActive());
        if ($status){
            $titreMail = 'Désactivation de structure';
            $message = 'La structure bien été désactivée ';
            $text='Votre structure a bien été désactivé vous ne pouvez plus acceder à votre profil';
        } else {
            $titreMail = 'Activation de structure';
            $message = 'La structure a bien été activée';
            $text='Votre structure a bien été activée vous pouvez désormais vous connecter à votre profil';
        }
        $em->flush();
        $email = (new TemplatedEmail())
            ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
            ->to($branch->getManager())
            ->subject($message)
            ->context(['text'=>$text,'titre'=>$titreMail])
            ->htmlTemplate('mails/activation_desactivation_compte.html.twig');
        $mailer->send($email);

        $emailClient = (new TemplatedEmail())
            ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
            ->to($branch->getClient()->getUser()->getEmail())
            ->subject($message)
            ->context(['titre'=>$titreMail .' '.$branch->getId(),'text'=>$text])
            ->htmlTemplate('mails/activation_desactivation_compte.html.twig');
        $mailer->send($emailClient);

        $this->addFlash('success',$message);

        return $this->render('branch/index.html.twig', [
            'controller_name' => 'BranchController',
        ]);

    }

    // Supprimer une branche en récupérant son id
    #[Route('/client/{id_client}/branch/{id_branch}/delete', name: 'app_branch_delete')]
    #[Entity('client', options: ['id' => 'id_client'])]
    #[Entity('branch', options: ['id' => 'id_branch'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Client $client,Branch $branch,ManagerRegistry $manager,MailerInterface $mailer): Response
    {
        $em = $manager->getManager();
        $em->remove($branch);
        $em->flush();

        $email = (new TemplatedEmail())
            ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
            ->to($branch->getManager())
            ->subject('Suppression de votre structure')
            ->context(['sujet'=>'Votre structure a été supprimée'])
            ->htmlTemplate('mails/suppression_compte.html.twig');
        $mailer->send($email);

        $emailClient = (new TemplatedEmail())
            ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
            ->to($branch->getClient()->getUser()->getEmail())
            ->subject('Suppression de structure')
            ->context(['sujet'=>'La structure dont l\'id est : '.$branch->getId() . ' est supprimée'])
            ->htmlTemplate('mails/email_notifications.html.twig');
        $mailer->send($emailClient);

        $this->addFlash('success','La structure a bien été supprimée');
        return $this->redirectToRoute('app_client_one',[
            'id'=>$client->getId()
        ]);
    }

    // Modifier les informations d'une branche
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

            // Envoyer une notification au utilisateur de la structure
            $email = (new TemplatedEmail())
                ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
                ->to($branch->getManager())
                ->subject('Modification de votre structure')
                ->context(['sujet'=>'Votre structure a été modifié connectez-vous pour voir plus de détails'])
                ->htmlTemplate('mails/email_notifications.html.twig');
            $mailer->send($email);

            // Envoyer un email à l'utilisateur du compte partenaire
            $emailClient = (new TemplatedEmail())
                ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
                ->to($data->getClient()->getUser()->getEmail())
                ->subject('Modification d\'une structure')
                ->context(['sujet'=>'Les informations de votre structure dont l\'id est : '.$branch->getId() . ' ont bien été changés'])
                ->htmlTemplate('mails/email_notifications.html.twig');
            $mailer->send($emailClient);

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
// le lien pour valider une nouvelle branche de la part du compte partenaire
    #[Route('/valide_branch/{token}')]
    public function validBranch($token,BranchRepository $branchRepository,
                                ManagerRegistry $managerRegistry,
                                ClientRepository $clientRepository,
                                MailerInterface $mailer,UserPasswordHasherInterface $hasher):Response
    {
            $em = $managerRegistry->getManager();
            $branche = $branchRepository->findOneBy([
                'token'=>$token
            ]);
            if (!$branche){
                throw new NoFileFoundException('Cette page n\'existe pas');
            }
            $branche->setToken(null);
            $client = $clientRepository->findOneBy([
                'id'=>$branche->getClient()
            ]);

            $userBranch = New User();
            $planPassword = md5(uniqid());
            $hashedPassword = $hasher->hashPassword($userBranch,$planPassword);
            $userBranch->setPassword($hashedPassword);
            $userBranch->setConfirmPwd($hashedPassword);
            $userBranch->setCreateAt(new \DateTime('now'));
            $userBranch->setEmail($branche->getManager());
            $userBranch->setRoles(['ROLE_USER']);
            $userBranch->setBranch($branche);
            $userBranch->setToken(md5(uniqid()));

            $email = (new TemplatedEmail())
                ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
                ->to($branche->getManager())
                ->subject('Activation de compte')
                ->context(['token'=>$userBranch->getToken()])
                ->htmlTemplate('mails/activation.html.twig');
            $mailer->send($email);

            $em->persist($userBranch);
            $em->flush();

            $email = (new TemplatedEmail())
                ->from(new Address('havikoro2004@gmail.com','Energy Fit Academy'))
                ->to($client->getTechnicalContact())
                ->subject('Activation de structure')
                ->context(['sujet'=>'Félicitation votre structure dont l\'id est '.$branche->getId().' est désormais active'])
                ->htmlTemplate('mails/validation_new_branch.html.twig');
            $mailer->send($email);
            $this->addFlash('success','Félicitation votre structure a bien été validée');
            return $this->redirectToRoute('app_login');
            return new Response('cc');
    }
}
