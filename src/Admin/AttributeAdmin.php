<?php

namespace App\Admin;

use App\Entity\Attribute;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AttributeAdmin extends AbstractAdmin
{
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('name')
            ->addIdentifier('type', null, [
                'template' => 'admin/attribute/_type.html.twig',
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('name')
            ->add('type', null, [], ChoiceType::class, [
                'choices' => [
                    'attribute.type.' . Attribute::TYPE_INT => Attribute::TYPE_INT,
                    'attribute.type.' . Attribute::TYPE_LIST => Attribute::TYPE_LIST,
                ]
            ]);
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('name')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'attribute.type.' . Attribute::TYPE_INT => Attribute::TYPE_INT,
                    'attribute.type.' . Attribute::TYPE_LIST => Attribute::TYPE_LIST,
                ]
            ])
            ->add('choices', TextareaType::class);

        $form->get('choices')->addModelTransformer(new CallbackTransformer(
            function ($array) {
                return $array ? implode("\n", $array) : '';
            },
            function ($string) {
                $array = explode("\n", $string);

                return array_filter(array_map('trim', $array));
            }
        ));
    }

}
