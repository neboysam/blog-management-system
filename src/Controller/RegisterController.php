<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class RegisterController extends AbstractController{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterUserType::class, $user);

        $form->handleRequest($request);
        //dd($form->getData());
        //dd($user);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd($form->getData()); the same content as $form->getData() on line 23
            //dd($user); the same content as $user on line 24
            $user->setRoles(['ROLE_USER']);
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('register/register.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }
}
