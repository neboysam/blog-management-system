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

    //create new or update existing category
    #[Route('/admin/category/add-or-update/{id}', name: 'app_admin_add_update_category', defaults: ['id' => null] )]
    public function modifyCategory(Request $request, $id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager): Response
    {
        if (!$id) {
            $category = new Category();
            $headingTitle = 'Create Category';
            $addFlashLabel = true;
        } else {
            $category = $categoryRepository->findOneById($id);
            $headingTitle = 'Update Category';
            $addFlashLabel = false;
        }
        
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            if ($addFlashLabel) {
                $label = 'created';
            } else {
                $label = 'updated';
            }
            $this->addFlash('success', 'Category ' . "'" . $category->getTitle() . "'" . ' is ' . $label . ' successfully.');
            return $this->redirectToRoute('app_admin_categories');
        } 
        
        return $this->render('admin/category/add-or-update-category.html.twig', [
            'categoryForm' => $form->createView(),
            'headingTitle' => $headingTitle
        ]);
    }
}
