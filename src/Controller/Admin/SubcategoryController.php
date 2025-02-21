<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SubcategoryController extends AbstractController{
    #[Route('/admin/subcategories', name: 'app_admin_subcategories')]
    public function index(): Response
    {
        return $this->render('admin/subcategory/subcategory.html.twig');
    }

    #[Route('/admin/add-subcategory', name: 'app_admin_add_subcategory')]
    public function addSubcategory(): Response
    {
        return $this->render('admin/subcategory/add-subcategory.html.twig');
    }
}
