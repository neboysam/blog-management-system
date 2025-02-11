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
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        //dd($request);
        $form = $this->createForm(RegisterUserType::class, $user);

        $form->handleRequest($request);
        //dd($request);
        //dd($form->getData());

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($request);
            dd($form->getData());
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('register/register.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }
}
