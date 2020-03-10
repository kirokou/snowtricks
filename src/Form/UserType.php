<?php

namespace App\Form;

use App\Entity\User;
use App\Form\TypeConfig;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserType extends TypeConfig
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, $this->getConfiguration("Votre email", "Ecrivez votre adresse mail"))
            ->add('firstname', TextType::class, $this->getConfiguration("Votre prénom","Ecrivez votre prénom"))
            ->add('lastname', TextType::class, $this->getConfiguration("Votre nom"," Ecrivez votre nom"))
            ->add('avatar', UrlType::class, $this->getConfiguration("URL de votre avatar","Ecrivez l'URL de votre avatar",false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
