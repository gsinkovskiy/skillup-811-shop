<?php

namespace App\Admin;

use App\Form\MoneyTransformer;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\CollectionType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OrderAdmin extends AbstractAdmin
{

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->addIdentifier('createdAt')
            ->addIdentifier('status')
            ->addIdentifier('firstName')
            ->addIdentifier('lastName')
            ->addIdentifier('phone')
            ->addIdentifier('email')
            ->addIdentifier('isPaid')
            ->addIdentifier('amount', null, [
                'template' => 'admin/order/_amount.html.twig',
            ])
        ;
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('user')
            ->add('createdAt')
            ->add('status')
            ->add('isPaid')
            ->add('amount', TextType::class)
            ->add('firstName')
            ->add('lastName')
            ->add('phone')
            ->add('email')
            ->add('address')
            ->add(
                'items',
                CollectionType::class,
                [
                    'by_reference' => false
                ],
                [
                    'edit' => 'inline',
                    'inline' => 'table',
                ]
            );
        ;

        $form->get('amount')->addModelTransformer(new MoneyTransformer());
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('user')
            ->add('createdAt')
            ->add('status')
            ->add('isPaid')
            ->add('amount')
            ->add('firstName')
            ->add('lastName')
            ->add('phone')
            ->add('email')
            ->add('address')
        ;
    }


}
