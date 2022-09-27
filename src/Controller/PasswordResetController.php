<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\ResetPawdType;
use App\Repository\UserRepository;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class PasswordResetController extends AbstractController
{
    #[Route('/password_reset', name: 'app_password_reset')]
    public function index(Request $request
        , UserRepository $userRepository, MailerInterface $mailer,ManagerRegistry $managerRegistry): Response
    {
        if ($this->getUser()){
            return $this->redirectToRoute('app_home');
        }
        $form = $this->createForm(ResetPawdType::class);
        $data = $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $existeEmail = $userRepository->findOneBy([
                'email' => $data->get('email')->getViewData()
            ]);
            if ($existeEmail) {
                $em = $managerRegistry->getManager();
                $token = md5(uniqid());
                $existeEmail->setToken($token);
                $em->flush();
                $emailClient = (new TemplatedEmail())
                    ->from(new Address('havikoro2004@gmail.com', 'Energy Fit Academy'))
                    ->to($existeEmail->getEmail())
                    ->subject('Changement de mot de passe')
                    ->context(['token' => $token])
                    ->htmlTemplate('mails/pawd_reset.html.twig');
                $mailer->send($emailClient);
                $this->addFlash('success', 'Votre demande de rénitialisation a bien été envoyé consulté votre email pour restaurer le mot de passe');
                return $this->redirectToRoute('app_login');
            } else {
                $this->addFlash('alert', 'Cette adresse email n\'existe pas');
            }
        }
        return $this->render('password_reset/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/pwd_reset/{token}', name: 'pwd_reset')]
    public function resetPwd($token, Request $request,
                             ManagerRegistry $managerRegistry,
                             UserPasswordHasherInterface $hasher,UserRepository $userRepository): Response
    {

        {
            // Vérifier le lien ou il y a le token si il y a un user avec ce token sinon reditrect vers page 404
            $user = $userRepository->findOneBy([
                'token' => $token
            ]);
            if (!$user) {
                throw $this->createNotFoundException();
            }
            // Créer le formulaire
            $form = $this->createForm(ChangePasswordType::class);
            $em = $managerRegistry->getManager();
            $data = $form->handleRequest($request);

            // Vérifier si le formulaire est submit la vérification si c'est valide se fait avec le formType et pas Entity
            // C'est pour ça je n'ai pas ajouté isValide()
            if ($form->isSubmitted()) {
                if ($data->get('password')->getViewData() !== $data->get('confirm_pass')->getViewData()) {
                    $this->addFlash('alert', 'Les mots de passe ne correspondent pas');
                } else {

                    // Hasher les deux mots de passe
                    $planPassword = $data->get('password')->getViewData();
                    $hachedPassword = $hasher->hashPassword($user, $planPassword);
                    $user->setPassword($hachedPassword);
                    $user->setConfirmPwd($hachedPassword);

                    // set le token en null
                    $user->setToken(null);

                    // valider et se rediriger vers la page login pour se connecter
                    $em->flush();
                    $this->addFlash('success', 'Votre mot de passe a bien été rénitialisé');
                    return $this->redirectToRoute('app_login');

                }
            }
            return $this->render('activation_compte/index.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }
}