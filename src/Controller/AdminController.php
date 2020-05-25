<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin", methods={"GET"})
     */
    public function index(TrickRepository $trickRepository, CommentRepository $commentRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
            'comments' => $commentRepository->findAll(),
        ]);
    }
}
