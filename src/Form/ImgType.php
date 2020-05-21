<?php

namespace App\Form;

use App\Entity\Img;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ImgType extends TypeConfig
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, ['label' => false, 'required' => false])
            ->add('alt', TextType::class, ['label' => false])
            ->add('fileName', TextType::class, ['required' => true])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Img::class,
        ]);
    }
}
