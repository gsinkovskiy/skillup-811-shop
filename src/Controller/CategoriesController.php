<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{

    /**
     * @Route("/categories", name="categories")
     */
    public function index(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('categories/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/categories/{id}", name="category_item")
     */
    public function item(Category $category, Request $request)
    {
        $form = $this->getFilterForm($category);
        $form->handleRequest($request);

        return $this->render('categories/item.html.twig', [
            'category' => $category,
            'filterForm' => $form->createView(),
        ]);
    }

    private function getFilterForm(Category $category)
    {
        $formBuilder = $this->createFormBuilder();
        $formBuilder->setMethod('get');

        foreach ($category->getAttributes() as $attribute) {
            $formBuilder->add('attr_min_' . $attribute->getId(), NumberType::class, ['required' => false]);
            $formBuilder->add('attr_max_' . $attribute->getId(), NumberType::class, ['required' => false]);
        }

        return $formBuilder->getForm();
    }

}
