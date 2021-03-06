<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Trick;
use App\Form\UserType;
use App\Service\Paginator;
use App\Repository\UserRepository;
use App\Repository\TrickRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_USER') and user == currentUser or is_granted('ROLE_ADMIN')", message="Vous n'avez pas le droit d'accéder à cette page.")
     * @Route("/{id}/{page<\d+>?1}", name="user_show", methods={"GET"})
     */
    public function show(User $currentUser, TrickRepository $trickRepository, CommentRepository $commentRepository, $page, Paginator $paginator): Response
    {
        $paginator->setEntityClass(Trick::class)
                ->setPage($page);

        return $this->render('user/show.html.twig', [
            'user' => $currentUser,
            'tricks' => $paginator->getData(['user'=>$this->getUser()]),
            'pages' => $paginator->getPages(['user'=>$this->getUser()]),
            'page' => $page

           // 'tricks' => $trickRepository->findByUser($this->getUser()),
           // 'comments' => $commentRepository->findByAuthor($this->getUser()),
        ]);
    }

    /**
     * @Security("is_granted('ROLE_USER') and user == currentUser or is_granted('ROLE_ADMIN')", message="Vous n'avez pas le droit d'accéder à cette page.")
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $currentUser): Response
    {
        $form = $this->createForm(UserType::class, $currentUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Super! votre profil à bien été modifié.');

            if ($this->isGranted('ROLE_USER')) {
                return $this->redirectToRoute('user_show', ['id' => $this->getUser()->getId()]);
            }

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $currentUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $em): Response
    {

        
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em->remove($user);
            $em->flush();
        }
        
        return $this->redirectToRoute('user_index');
    }
}
