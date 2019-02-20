<?php


namespace App\Admin;


use App\Entity\Product;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductAdmin extends AbstractAdmin
{

    /**
     * @var CacheManager
     */
    private $cacheManager;

    public function __construct(string $code, string $class, string $baseControllerName, CacheManager $cacheManager)
    {
        parent::__construct($code, $class, $baseControllerName);

        $this->cacheManager = $cacheManager;
    }


    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->addIdentifier('name')
            ->addIdentifier('description')
            ->addIdentifier('price')
            ->add('isTop')
            ->add('category');
    }

    protected function configureFormFields(FormMapper $form)
    {
        $cacheManager = $this->cacheManager;

        $form
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('isTop')
            ->add('category')
            ->add('image', VichImageType::class, [
                'required' => false,
                'image_uri' => function (Product $product, $resolvedUri) use ($cacheManager) {
                    if (!$resolvedUri) {
                        return null;
                    }

                    return $cacheManager->getBrowserPath($resolvedUri, 'squared_thumbnail');
                }
            ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('isTop')
            ->add('category');
    }


}
