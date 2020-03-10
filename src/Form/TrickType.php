<?php

namespace App\Form;

use App\Entity\Trick;
use App\Form\ImgType;
use App\Form\MovieType;
use App\Entity\Category;
use App\Form\TypeConfig;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrickType extends TypeConfig
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                $this->getConfiguration("Titre de la figure","Entrez le titre de la figure")
            )
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
                MovieType::class
            )
            ->add(  // OneToMany
                'imgs', 
                CollectionType::class, 
                [
                'entry_type' => ImgType::class,
                'allow_add'=> true,
                'allow_delete'=> true,
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
