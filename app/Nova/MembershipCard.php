<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class MembershipCard extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\MembershipCard::class;
    public static function label()
    {
        return __('Membership Card');
    }
    public static function group()
    {
        return __("Chain Store");
    }
    public static function icon()
    {
        return view("nova::svg.credit-card");
    }
//    public static function availableForNavigation(Request $request): bool
//    {
//        return false;
//    }
    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
//            ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make(__('Store'), 'store', Store::class)->withoutTrashed(),
            BelongsTo::make(__('Clerk'), 'clerk', Clerk::class),
            BelongsTo::make(__('Customer'), 'customer', Customer::class),
            Text::make(__('Card No'), 'card_no'),
            Currency::make(__('Total Price'), 'total_price')->currency('CNY'),
            Currency::make(__('Paid Price'), 'paid_price')->currency('CNY'),
            Select::make(__('Status'), 'status')->options(\App\Models\MembershipCard::statusOptions())->displayUsingLabels(),
            Text::make(__('Comment'), 'comment'),
            new Panel(__('Validity Period'), [
                Select::make(__('Validity Type'), 'validity_type')->options(\App\Models\MembershipCard::periodOptions())->displayUsingLabels()->onlyOnForms(),
                Number::make(__('Validity Period'), 'validity_period')->onlyOnForms(),
                Date::make(__('Validity Start'), 'validity_start')->onlyOnForms(),
                Date::make(__('Validity To'), 'validity_to')->onlyOnForms(),
                Text::make(__('Validity Period'))->displayUsing(function(){
                    return $this->validity_period . $this->periodLabel()."(".$this->validity_start->toDateString() .'-'. $this->validity_to->toDateString().")";
                })->exceptOnForms()
            ])
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    // public function cards(Request $request)
    // {
    //     return [];
    // }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
