<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new \Twig_Filter('money', [$this, 'formatMoney']),
        ];
    }

    public function formatMoney($value)
    {
        return twig_localized_currency_filter($value / 100, 'UAH');
    }

}
