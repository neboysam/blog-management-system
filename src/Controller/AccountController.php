<?php

namespace App\Controller;

use App\Form\ModifyPasswordType;
use App\Form\ModifyPasswordTemplateType;
use Doctrine\ORM\EntityManagerInterface;
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
    public function modifyPasswordController(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        //get encrypted password from current user (database)
        $user = $this->getUser();
        //dd($user); //current encrypted user password

        //current user password from db, encrypted
        $currentDatabasePassword = $user->getPassword();

        $form = $this->createForm(ModifyPasswordType::class, $user);

        //dd($form->get('currentPassword')->getData()); //value is null before handleRequest($request)
        //dd($user); //current encrypted user password
        //dd($form->getData()); //current encrypted user password

        $form->handleRequest($request);
        //dd($user); //new encrypted user password
        //dd($form->getData()); //new encrypted user password

        //get current password value (in plain text) from form
        $currentPlainTextPassword = $form->get('currentPassword')->getData();

        //get new password (in plain text) from form
        $newPlainTextPassword = $form->get('plainPassword')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($currentDatabasePassword);
            
            if ($passwordHasher->isPasswordValid($user, $currentPlainTextPassword)) {
                $newHashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $newPlainTextPassword
                );
            } else {
                    $this->addFlash('info', "Le mot de passe ne pouvait pas etre modifiee");
                    return $this->redirectToRoute('app_account_modify_password_controller');
              };

            $user->setPassword($newHashedPassword);

            $entityManager->flush();

            $this->addFlash('success', "Le mot de passe vient d'etre modifiee");
            return $this->redirectToRoute('app_account');            
        }

        return $this->render('account/modify_password.html.twig', [
            'modifyPasswordForm' => $form->createView()
        ]);
    }

    #[Route('/compte/modifier-mot-de-passe-template', name: 'app_account_modify_password_template')]
    public function modifyPasswordTemplate(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        //dd($user); //current encrypted user password from database
        
        $form = $this->createForm(ModifyPasswordTemplateType::class, $user, [
            'passwordHasher' => $passwordHasher
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', "Le mot de passe vient d'etre modifiee");
            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/modify_password.html.twig', [
            'modifyPasswordForm' => $form->createView()
        ]);
    }
}
