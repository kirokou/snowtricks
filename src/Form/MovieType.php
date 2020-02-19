<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MovieType extends AbstractType
{

    private function getConfiguration($label, $placeholder, $required=true)
    {
        return [
            'label'=> $label,
            'attr'=> [
                'placeholder'=>$placeholder
            ],
            'required'=>$required
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                $this->getConfiguration("Titre de la vidéo","Ajouter le titre de la vidéo")
            )
            ->add(
                'src',
                TextType::class,
                $this->getConfiguration("Lien de la vidéo","Ajouter le lien de la vidéo")
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
