<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Service\Paginator;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
    private $em;

    /**
     * @param  mixed $em
     * @return void
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{page<\d+>?1}", name="comment_index", methods={"GET"})
     */
    public function index(CommentRepository $commentRepository, $page, Paginator $paginator): Response
    {
        $paginator->setEntityClass(Comment::class)
                ->setPage($page);

        return $this->render('comment/index.html.twig', [
            'comments' => $paginator->getData(),
            'pages' => $paginator->getPages(),
            'page' => $page
        ]);
    }

    /**
     * @IsGranted({"ROLE_ADMIN","ROLE_USER"})
     * @Route("/new", name="comment_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($comment);
            $this->em->flush();

            return $this->redirectToRoute('comment_index');
        }

        return $this->render('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comment_show", methods={"GET"})
     */
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

     /**
     * @IsGranted({"ROLE_ADMIN","ROLE_USER"})
     * @Route("/{id}/edit", name="comment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Comment $comment, EntityManagerInterface $em): Response
    {
        //Check if trick is this current simple_user's trick
        if ($this->getUser()->getRoles() !== 'ROLE_ADMIN' && $comment->getAuthor()->getId() !== $this->getUser()->getId()) {
            throw $this->createNotFoundException('Vous ne disposez pas des droits nécessaires pour la modification de ce commentaire.');
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setUpdatedAt(new \DateTime('now'));
            $em->flush();

            $this->addFlash('success', 'Super! votre commentaire à bien été modifié.');

            if ($this->getUser()->getRoles() !== 'ROLE_ADMIN') {
                return $this->redirectToRoute('trick_show', ['id'=>$comment->getTrick()->getId()]);
            }

            return $this->redirectToRoute('trick_index');
        }

        return $this->render('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted({"ROLE_ADMIN","ROLE_USER"})
     * @Route("/{id}", name="comment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->getUser()->getRoles() !== 'ROLE_ADMIN' && $comment->getAuthor()->getId() !== $this->getUser()->getId()) {
            throw $this->createNotFoundException('Vous ne disposez pas des droits nécessaires pour la suppression de ce commentaire.');
        }

        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
           $this->em->remove($comment);
           $this->em->flush();
        }

        if ($this->getUser()->getRoles() !== 'ROLE_ADMIN') {
            return $this->redirectToRoute('trick_show', ['id'=>$comment->getTrick()->getId()]);
        }
        
        return $this->redirectToRoute('comment_index');
    }
}
