<?php

namespace App\Controller;

use App\Form\TrickType;
use App\Repository\ImgRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/img")
 */
class ImgController extends AbstractController
{
    /**
     * @IsGranted({"ROLE_ADMIN","ROLE_USER"})
     * @Route("/{id}", name="img_delete")
     */
    public function delete(ImgRepository $imgRepository, TrickRepository $trickRepository,  $id, EntityManagerInterface $em): Response
    {
        $img = $imgRepository->find($id);
        $em->remove($img);
        $em->flush();

        $trick = $trickRepository->find($img->getTrick());
        $form = $this->createForm(TrickType::class, $trick);

        return $this->render('trick/edit.html.twig', [
            'trick' => $trickRepository->find($img->getTrick()),
            'form' => $form->createView()
        ]);

    }
}
