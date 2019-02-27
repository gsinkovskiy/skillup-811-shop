<?php

namespace App\Form;

use Symfony\Component\Form\CallbackTransformer;

class MoneyTransformer extends CallbackTransformer
{
    public function __construct()
    {
        parent::__construct(
            function ($valueInCents) {
                return $valueInCents / 100;
            },
            function ($value) {
                return round($value * 100);
            }
        );
    }

}
