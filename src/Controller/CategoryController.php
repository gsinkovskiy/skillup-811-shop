<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    /**
     * @Route("/category/{slug}", name="category", requirements={"slug": "^[-\w]+$"})
     */
    public function index($slug = 'all')
    {
        switch ($slug) {
            case 'tv': $name = 'телевизоры'; break;
            case 'media': $name = 'мультимедиа'; break;
            case 'all': $name = 'все'; break;
            default:
                throw $this->createNotFoundException('Категория не найдена');
        }

        return $this->render('category/index.html.twig', [
            'category_code' => $slug,
            'category_name' => $name,
        ]);
    }

}
