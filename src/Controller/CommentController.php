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
     * @Security("is_granted('ROLE_USER') and user == comment.author or is_granted('ROLE_ADMIN')", message="Vous ne pouvez pas supprimer ce commentaire.")
     * @Route("/{id}", name="comment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
           $this->em->remove($comment);
           $this->em->flush();
        }

        return $this->redirectToRoute('comment_index');
    }
}
