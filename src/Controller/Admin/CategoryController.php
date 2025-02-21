<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CategoryController extends AbstractController{
    #[Route('/admin/categories', name: 'app_admin_categories')]
    public function showCategories(CategoryRepository $categoryRepo): Response
    {
        $categories = $categoryRepo->findAll();
        return $this->render('admin/category/category.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/admin/add-category', name: 'app_admin_add_category')]
    public function addCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('success', 'Category ' . "'" . $category->getTitle() . "'" . ' is added successfully.');
            return $this->redirectToRoute('app_admin_categories');
        } 
        
        return $this->render('admin/category/add-category.html.twig', [
            'categoryForm' => $form->createView()
        ]);
    }
}
