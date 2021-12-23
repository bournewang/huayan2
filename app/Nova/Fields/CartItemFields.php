<?php

namespace App\Nova\Fields;

use Laravel\Nova\Fields\Text;

class CartItemFields
{
    /**
     * Get the pivot fields for the relationship.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            Text::make(__('Price'), 'price'),//->displayUsing(function($val){return moneyformat( $val);}),
            Text::make(__('Quantity'), 'quantity'),
            Text::make(__('Subtotal'), 'subtotal')//->displayUsing(function($val){return moneyformat( $val);})
        ];
    }
}