<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ModifyPasswordTemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'mapped' => false
            ])
            ->add('password', PasswordType::class, [
                'label' => "New Password"
            ])
            ->add('submit', SubmitType::class)
            ->addEventListener(FormEvents::SUBMIT, function(SubmitEvent $event): void {
                $user = $event->getData();
                $form = $event->getForm();
                $user = $form->getConfig()->getOptions()['data'];
                //dd($user->getPassword()); //12345
                //dd($user);
                /* $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];
                $currentDatabasePlainTextPassword = $form->get('currentPassword')->getData(); //plain text password from form

                $isValid = $passwordHasher->isPasswordValid($user, $currentDatabasePlainTextPassword);

                if (!$isValid) {
                    $form->get('currentPassword')->addError(new FormError("Votre mot de passe actuel n'est pas conforme. Veuillez verifier votre saisie."));
                } */
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
