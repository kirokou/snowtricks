<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Comment;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $trickRepository = $entityManager->getRepository(Trick::class);
        $tricks = $trickRepository->findAll();

        $commentRepository=$entityManager->getRepository(Comment::class);
        $comments=$commentRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'tricks'=>$tricks,
            'comments'=>$comments,
        ]);
    }
}
