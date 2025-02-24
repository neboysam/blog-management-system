<?php

namespace App\Controller\Admin;

use App\Entity\Subcategory;
use App\Form\SubcategoryType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SubcategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SubcategoryController extends AbstractController{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin/subcategories', name: 'app_admin_subcategories')]
    public function showSubcategories(SubcategoryRepository $subcategoryRepo, PaginatorInterface $paginator, Request $request): Response
    {
        //$subcategories = $subcategoryRepo->findAll();
        $subcategories = $paginator->paginate(
            $subcategoryRepo->findAllWithPagination(), /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            10 /* limit per page */
        );
        return $this->render('admin/subcategory/subcategories.html.twig', [
            'subcategories' => $subcategories
        ]);
    }

    //create new or update existing category
    #[Route('/admin/subcategory/add-or-update/{id}', name: 'app_admin_add_update_subcategory', defaults: ['id' => null] )]
    public function modifySubcategory(Request $request, $id, SubcategoryRepository $subcategoryRepo): Response
    {
        if (!$id) {
            $subcategory = new Subcategory();
            $headingTitle = 'Create Subcategory';
            $addFlashLabel = true;
        } else {
            $subcategory = $subcategoryRepo->findOneById($id);
            $headingTitle = 'Update Subcategory';
            $addFlashLabel = false;
        }
        
        $form = $this->createForm(SubcategoryType::class, $subcategory);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($subcategory);
            $this->entityManager->flush();

            if ($addFlashLabel) {
                $label = 'created';
            } else {
                $label = 'updated';
            }
            $this->addFlash('success', 'Subcategory ' . "'" . $subcategory->getTitle() . "'" . ' is ' . $label . ' successfully.');
            return $this->redirectToRoute('app_admin_subcategories');
        } 
        
        return $this->render('admin/subcategory/add-or-update-subcategory.html.twig', [
            'subcategoryForm' => $form->createView(),
            'headingTitle' => $headingTitle
        ]);
    }

    #[Route('/admin/subcategory/delete/{id}', name: 'app_admin_delete_subcategory')]
    public function deleteSubcategory($id, SubcategoryRepository $subcategoryRepo): Response
    {
        $subcategory = $subcategoryRepo->findOneById($id);

        $this->entityManager->remove($subcategory);

        return $this->render('admin/subcategory/subcategories.html.twig');
    }
}
