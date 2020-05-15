<?php

namespace App\Controller;

use App\Entity\Img;
use App\Form\TrickType;
use App\Repository\ImgRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/img")
 */
class ImgController extends AbstractController
{
    /**
     * @Route("/{id}", name="img_delete")
     */
    public function delete(ImgRepository $imgRepository, TrickRepository $trickRepository,  $id, EntityManagerInterface $entityManager): Response
    {
        $img = new Img();
        $img  = $imgRepository->find($id);
        $entityManager->remove($img);
        $entityManager->flush();

        $trick = $trickRepository->find($img->getTrick());
        $form = $this->createForm(TrickType::class, $trick);

        return $this->render('trick/edit.html.twig', [
            'trick' => $trickRepository->find($img->getTrick()),
            'form' => $form->createView()
        ]);

    }
}
