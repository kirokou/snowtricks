<?php

namespace App\Form;


use App\Entity\Group;
use App\Entity\Trick;
use App\Form\ImgType;
use App\Form\MovieType;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add(
                'category', //ManytoOne
                EntityType::class,
                [
                'class' => Category::class,
                'choice_label' => 'title',
                ]
            )  
            ->add(
                'movie',  //OnetoOne // sans trick au niveau de movie warning
                MovieType::class, 
                [ 
                'required' => false,
                'empty_data' => null
                ]
            )
            ->add(  // OneToMany
                'imgs', 
                CollectionType::class, [
                'entry_type' => ImgType::class
                ]
            )
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
