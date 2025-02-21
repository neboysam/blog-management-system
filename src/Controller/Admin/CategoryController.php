<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController{
    #[Route('/admin/categories', name: 'app_admin_categories')]
    public function index(): Response
    {
        return $this->render('admin/category/category.html.twig');
    }

    #[Route('/admin/add-category', name: 'app_admin_add_category')]
    public function addCategory(): Response
    {
        return $this->render('admin/category/add-category.html.twig');
    }
}
