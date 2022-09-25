<?php

namespace App\Controller;
use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ActivationCompteController extends AbstractController
{
    #[Route('/activation/{token}', name: 'app_activation_compte')]
    public function index($token,UserRepository $userRepository,
                          UserPasswordHasherInterface $hasher,Request $request,
                          ManagerRegistry $managerRegistry): Response
    {
        // Vérifier le lien ou il y a le token si il y a un user avec ce token sinon reditrect vers page 404
        $user = $userRepository->findOneBy([
           'token'=>$token
        ]);
        if (!$user){
            throw $this->createNotFoundException();
        }
        // Créer le formulaire
        $form = $this->createForm(ChangePasswordType::class);
        $em = $managerRegistry->getManager();
        $data =$form->handleRequest($request);

        // Vérifier si le formulaire est submit la vérification si c'est valide se fait avec le formType et pas Entity
        // C'est pour ça je n'ai pas ajouté isValide()
        if ($form->isSubmitted()){
            if ($data->get('password')->getViewData() !== $data->get('confirm_pass')->getViewData()){
                $this->addFlash('alert','Les mots de passe ne correspondent pas');
            } else {

                // Hasher les deux mots de passe
                $planPassword = $data->get('password')->getViewData();
                $hachedPassword = $hasher->hashPassword($user,$planPassword);
                $user->setPassword($hachedPassword);
                $user->setConfirmPwd($hachedPassword);

                // set le token en null
                $user->setToken(null);

                // valider et se rediriger vers la page login pour se connecter
                $em->flush();
                $this->addFlash('success','Félicitation votre compte est désormais actif');
                return $this->redirectToRoute('app_login');

            }
        }
        return $this->render('activation_compte/index.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
