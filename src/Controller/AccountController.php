<?php

namespace App\Controller;

use App\Form\ModifyPasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AccountController extends AbstractController{
    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/account.html.twig');
    }

    #[Route('/compte/modifier-mot-de-passe-controller', name: 'app_account_modify_password_controller')]
    public function modifyPasswordController(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        //get encrypted password from current user (database)
        $user = $this->getUser();
        $userEncryptedPassword = $user->getPassword();

        $form = $this->createForm(ModifyPasswordType::class, $user);

        //dd($form->get('currentPassword')->getData());

        //get current password (in plain text) from form
        $form->handleRequest($request);
        $currentPlainPassword = $form->get('currentPassword')->getData();
        
        //dd($currentPlainPassword);

        //get new password (in plain text) from form (only after handleRequest($request))
        $newPlainPassword = $form->getData()->getPassword();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($passwordHasher->isPasswordValid($user, $currentPlainPassword)) {
                $newHashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $newPlainPassword
                );
                dd($newHashedPassword);
            }
        }

        return $this->render('account/modify_password.html.twig', [
            'modifyPasswordForm' => $form->createView()
        ]);
    }

    #[Route('/compte/modifier-mot-de-passe-template', name: 'app_account_modify_password_template')]
    public function modifyPasswordTemplate(): Response
    {
        $form = $this->createForm(ModifyPasswordType::class);

        return $this->render('account/modify_password.html.twig', [
            'modifyPasswordForm' => $form->createView()
        ]);
    }
}
