<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/sign-up", name="app_register", methods={"GET","POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer, EntityManagerInterface $em): Response
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

            $em->persist($user);
            $em->flush();

            // On crée le message
            $message = (new \Swift_Message('Nouveau compte'))
                ->setFrom('borgine@toto.fr')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/activation.html.twig',
                        ['token' => $user->getActivationToken()]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);

            return $this->render('registration/welcome.html.twig');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/activation/{token}", name="activation", methods={"GET"})
     */
    public function activation($token, UserRepository $user, Request $request, LoginFormAuthenticator $authenticator, GuardAuthenticatorHandler $guardHandler, EntityManagerInterface $em): Response
    {
        $user = $user->findOneBy(['activation_token' => $token]);

        if (!$user) {
            throw $this->createNotFoundException("Ce token n'est pas valide ou a déjà été utilisé. <br/> Connectez-vous à votre compte si vous n'y arrivez pas contactez le support. ");
        }
      
        // On supprime le token
        $user->setActivationToken(null)
            ->setroles(['ROLE_USER']);

        $em->persist($user);
        $em->flush();

        // On authentififie l'utilisateur
        return $guardHandler->authenticateUserAndHandleSuccess(
            $user,
            $request,
            $authenticator,
            'main' // firewall name in security.yaml
        );

        $this->addFlash('success', "Votre compte a bien été activée.");

        // On retourne à l'accueil
        return $this->redirectToRoute('home');
    }
}
