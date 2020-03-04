<?php

namespace App\Controller;

use App\Entity\Trick;
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

        $trickRepository=$entityManager->getRepository(Trick::class);
        $tricks=$trickRepository->findAll();
        $nombre= count($tricks);

        return $this->render('admin/index.html.twig',[
            'nombre'=>$nombre
        ]);
    }
}
