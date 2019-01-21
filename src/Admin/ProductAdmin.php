<?php


namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ProductAdmin extends AbstractAdmin
{

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
        $form
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('isTop')
            ->add('category');
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
