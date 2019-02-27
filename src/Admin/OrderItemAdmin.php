<?php

namespace App\Admin;

use App\Form\MoneyTransformer;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class OrderItemAdmin extends AbstractAdmin
{

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('product')
            ->add('quantity', null, [
                'attr' => ['class' => 'js-quantity js-recalc-cost'],
            ])
            ->add('price', null, [
                'attr' => ['class' => 'js-price js-recalc-cost'],
            ])
            ->add('cost', null, [
                'attr' => ['class' => 'js-cost'],
            ])
        ;

        $form->get('price')->addModelTransformer(new MoneyTransformer());
        $form->get('cost')->addModelTransformer(new MoneyTransformer());
    }

}
