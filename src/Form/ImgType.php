<?php

namespace App\Form;

use App\Entity\Img;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ImgType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, ['label' => false])
        /*
            ->add(
                'file',
                FileType::class,
                [
                    'label' => false,
                    'mapped' => false,
                    'required' => false,
                    'constraints' => array(
                        new File())
                ]
            )
        */
            //->add('ext')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Img::class,
        ]);
    }
}
