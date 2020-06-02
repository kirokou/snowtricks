<?php

namespace App\Controller;

use DateTime;
use App\Entity\Img;
use App\Entity\Movie;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\TrickType;
use App\Form\CommentType;
use App\Service\Paginator;
use App\Repository\TrickRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/trick")
 */
class TrickController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{page<\d+>?1}", name="trick_index", methods={"GET"})
     */
    public function index(TrickRepository $trickRepository, $page, Paginator $paginator): Response
    {
        $paginator->setEntityClass(Trick::class)
                ->setPage($page);

        return $this->render('admin/trick/index.html.twig', [
            'tricks' => $paginator->getData(),
            'pages' => $paginator->getPages(),
            'page' => $page
        ]);
    }

    /**
     * @IsGranted({"ROLE_ADMIN","ROLE_USER"})
     * @Route("/new", name="trick_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Manage Img collectionType
            foreach ($trick->getImgs() as $img) {
                $img->setTrick($trick);
                $trick->addImg($img);
            }

            $trick->setCreatedAt(new DateTime('now'))
                ->setUser($this->getUser());

            $em->persist($trick);
            $em->flush();

            $this->addFlash('success', 'Super! votre annonce à bien été ajouté.');

            if ($this->getUser()->getRoles() !== 'ROLE_ADMIN') {
                return $this->redirectToRoute('user_show', ['id'=>$this->getUser()->getId()]);
            }
            
            return $this->redirectToRoute('trick_index');
        }

        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
    * @Route("/{id}/show", name="trick_show", methods={"GET", "POST"})
    */
    public function show(Trick $trick, Request $request, CommentRepository $commentRepository, EntityManagerInterface $em): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser())
                ->setTrick($trick)
                ->setCreatedAt(new DateTime('now'));
          
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('trick_show', ['id' => $trick->getId()]);
        }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'comments' => $commentRepository->findBy(['trick' => $trick], ['id' => 'DESC']),
            'form' => $form->createView()
        ]);
    }

    /**
     * @IsGranted({"ROLE_ADMIN","ROLE_USER"})
     * @Route("/{id}/edit", name="trick_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Trick $trick, EntityManagerInterface $em): Response
    {
        //Check if trick is this current simple_user's trick
        if ($this->getUser()->getRoles() !== 'ROLE_ADMIN' && $trick->getUser()->getId() !== $this->getUser()->getId()) {
            throw $this->createNotFoundException('Vous ne disposez pas des droits nécessaires pour la modification de ce trick.');
        }

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Manage collectionType
            foreach ($trick->getImgs() as $img) {
                $img->setTrick($trick);
                $trick->addImg($img);
            }
            
            $trick->setUpdatedAt(new DateTime('now'));
            $em->persist($trick);
            $em->flush();

            $this->addFlash('success', 'Super! votre annonce à bien été modifié.');

            if ($this->getUser()->getRoles() !== 'ROLE_ADMIN') {
                return $this->redirectToRoute('user_show', ['id'=>$this->getUser()->getId()]);
            }

            return $this->redirectToRoute('trick_index');
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted({"ROLE_ADMIN","ROLE_USER"})
     * @Route("/{id}", name="trick_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Trick $trick, EntityManagerInterface $em): Response
    {
        if ($this->getUser()->getRoles() !== 'ROLE_ADMIN' && $trick->getUser()->getId() !== $this->getUser()->getId()) {
            throw $this->createNotFoundException('Vous ne disposez pas des droits nécessaires pour supprimer ce trick.');
        }

        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $em->remove($trick);
            $em->flush();
        }
        
        $this->addFlash('success', 'Vous avez malheureusement supprimer un trick. Pensez à en ajouter d\'autres.');

        if ($this->getUser()->getRoles() !== 'ROLE_ADMIN') {
            return $this->redirectToRoute('user_show', ['id'=>$this->getUser()->getId()]);
        }

        return $this->redirectToRoute('trick_index');
    }
}
