<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Your email addresse',
                'attr' => [
                    'placeholder' => 'Enter your email addresse'
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => new Length([
                    'min' => 4,
                    'max' => 15
                ]),
                'first_options'  => [
                    'label' => 'Your password',
                    'attr' => [
                        'placeholder' => 'Enter your password'
                    ],
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'label' => 'Confirm your password',
                    'attr' => [
                        'placeholder' => 'Confirm your password'
                    ],
                ],
                'mapped' => false,
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Your name',
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 10
                ]),
                'attr' => [
                    'placeholder' => 'Enter your name'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Your surname',
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 20
                ]),
                'attr' => [
                    'placeholder' => 'Enter your surname'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints' => [
                new UniqueEntity(fields: ['email']), //['fields' => ['email', 'username']]
            ],
        ]);
    }
}
