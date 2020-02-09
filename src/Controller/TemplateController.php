<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TemplateController extends AbstractController
{
    /**
     * @Route("/template/show", name="Show")
     */
    public function show()
    {
        return $this->render('template/show.html.twig', [
            'controller_name' => 'TemplateController',
        ]);
    }

     /**
     * @Route("/template/edit", name="edit")
     */
    public function edit()
    {
        return $this->render('template/edit.html.twig', [
            'controller_name' => 'TemplateController',
        ]);
    }

     /**
     * @Route("/template/forgot", name="forgot")
     */
    public function forgot()
    {
        return $this->render('template/forgot.html.twig', [
            'controller_name' => 'TemplateController',
        ]);
    }

     /**
     * @Route("/template/login", name="login")
     */
    public function login()
    {
        return $this->render('template/login.html.twig', [
            'controller_name' => 'TemplateController',
        ]);
    }

     /**
     * @Route("/template/reset", name="reset")
     */
    public function reset()
    {
        return $this->render('template/reset.html.twig', [
            'controller_name' => 'TemplateController',
        ]);
    }

     /**
     * @Route("/template/registration", name="registration")
     */
    public function registration()
    {
        return $this->render('template/registration.html.twig', [
            'controller_name' => 'TemplateController',
        ]);
    }

}
