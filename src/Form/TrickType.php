<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\Category;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TrickType extends TypeConfig
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                $this->getConfiguration('Titre de la figure', 'Entrez le titre de la figure.')
            )
            ->add(
                'description',
                TextareaType::class,
                $this->getConfiguration('Description de la figure', 'Entrez la description de la figure.')
            )
            ->add(
                'category',
                EntityType::class,
                [
                'class' => Category::class,
                'label'=> 'Catégorie',
                'choice_label' => 'title',
                ]
            )
            ->add(
                'movie',
                MovieType::class
            )
           ->add(
                'imgs',
                CollectionType::class,
                [
                'entry_type' => ImgType::class,
                'label'=> 'Ajouter une ou plusieurs images',
                'allow_add'=> true,
                'allow_delete'=> true,
                ]
            )
        /*
            ->add(
                'imgs',
                FileType::class,
                [
                    'multipe' => true, 
                    'mapped' => false
                ]
            )
        */
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
