<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ModifyPasswordTemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'mapped' => false
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => new Length([
                    'min' => 4,
                    'max' => 15
                ]),
                'first_options'  => [
                    'label' => 'Votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Indiquez votre nouveau mot de passe'
                    ],
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'label' => 'Confirmez votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Confirmez votre nouveau mot de passe'
                    ],
                ],
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class)
            ->addEventListener(FormEvents::SUBMIT, function(SubmitEvent $event): void {
                //$user = $event->getData(); //user from db
                $form = $event->getForm();
                $userFromDatabase = $form->getConfig()->getOptions()['data']; //identical to $event->getData()
                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];
                $currentDatabasePlainTextPassword = $form->get('currentPassword')->getData(); //plain text password from form

                $isValid = $passwordHasher->isPasswordValid($userFromDatabase, $currentDatabasePlainTextPassword);

                if (!$isValid) {
                    $form->get('currentPassword')->addError(new FormError("Votre mot de passe actuel n'est pas conforme. Veuillez verifier votre saisie."));
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'passwordHasher' => null
        ]);
    }
}
