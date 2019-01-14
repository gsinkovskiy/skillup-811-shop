<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    /**
     * @Route("/products/{id}", name="products_item")
     */
    public function item(Product $product)
    {
        return $this->render('products/item.html.twig', [
            'product' => $product,
        ]);
    }

}
