<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $products = $productRepository->findBy(
            ['isTop' => true],
            ['name' => 'ASC']
        );

        $controller = $this;
        $tree = $categoryRepository->childrenHierarchy(
            null,
            false,
            [
                'decorate' => true,
                'representationField' => 'name',
                'html' => true,
                'nodeDecorator' => function($node) use ($controller) {
                    $url = $controller->generateUrl('category_item', ['id' => $node['id']]);
                    return '<a href="' . $url . '">' . $node['name'] . '</a>';
                }
            ]
        );

        return $this->render('default/index.html.twig', [
            'products' => $products,
            'tree' => $tree,
        ]);
    }

}
