<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/{limit<\d+>?}", name="home")
     */
    public function index(Request $request, TrickRepository $trickRepository, $limit): Response
    {
        if(!isset($limit)){
            $limit = 3;
        }

        return $this->render('home/index.html.twig', [
            'tricks' => $trickRepository->findBy([],[], $limit, 0),
        ]);
    }
}
