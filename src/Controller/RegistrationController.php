<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/sign-up", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            // Make and set token to user
            $user->setActivationToken(md5(uniqid()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            // On crée le message
            $message = (new \Swift_Message('Nouveau compte'))
                ->setFrom('borgine@toto.fr')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/activation.html.twig', ['token' => $user->getActivationToken()]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);

            // On génère un message et en envoi la page de bienvenue
            return $this->render('registration/welcome.html.twig');          
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    /**
     * @Route("/activation/{token}", name="activation")
     */
    public function activation($token, UserRepository $user, Request $request, LoginFormAuthenticator $authenticator, GuardAuthenticatorHandler $guardHandler)
    {
        $user = $user->findOneBy(['activation_token' => $token]);
        dump($token);
        dump($user);

        if(!$user){
            // On renvoie une erreur 404
            throw $this->createNotFoundException("Cet utilisateur n'existe pas");
        }
        else{
            // On supprime le token
            $user->setActivationToken(null);
             // On attribue un ROLE_USER // Il sera update Admin par l'admin
            $user->setroles(['ROLE_USER']);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // On authentififie l'utilisateur
        return $guardHandler->authenticateUserAndHandleSuccess(
            $user,
            $request,
            $authenticator,
            'main' // firewall name in security.yaml
        );

        // On génère un message
        $this->addFlash('success', "Votre compte a bien été activée.");

        //Faire une condition sur la page qui sera retourner à l'utilisateur selon son role
        // On retourne à l'accueil
        return $this->redirectToRoute('home');
    }
}