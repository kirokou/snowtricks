<?php

namespace App\Form;

use App\Entity\User;
use App\Form\TypeConfig;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends TypeConfig
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, $this->getConfiguration('Votre adresse mail','Ecrivez votre adresse mail'))
            ->add('firstname', TextType::class, $this->getConfiguration('Votre prénom','Ecrivez votre Prénom'))
            ->add('lastname', TextType::class, $this->getConfiguration('Votre nom','Ecrivez votre nom'))
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label'=> 'J\'accepte la conservation de mes données personnelles dans le respect de la RGPD',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez cochez cette case pour donner votre accord pour la conservation de vos données personnelles.',
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => $this->getConfiguration('Votre mot de passe','Entrez votre mot de passe'),
                'second_options' => $this->getConfiguration('Confirmez votre mot de passe','Entrez à nouveau votre mot de passe'),
            )
            )

            /*
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            */
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
