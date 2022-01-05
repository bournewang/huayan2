<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class LogisticProgress extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\LogisticProgress::class;
    public static function label()
    {
        return __('Logistic Progress');
    }
    public static function group()
    {
        return __("Mall");
    }

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
            ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make(__('Order'), 'order', Order::class),
            Select::make(__('Status'), 'status')->options(\App\Models\LogisticProgress::statusOptions())->displayUsingLabels(),
            Text::make(__('Query Times'), 'queryTimes'),
            Text::make(__('Fee Num'), 'fee_num'),
            Text::make(__('Logistic'), 'expTextName'),
            Text::make(__('Message'), 'msg'),
            Text::make(__('Tel'), 'tel'),
            Boolean::make(__('Flag'), 'flag'),
            Text::make(__('Logo'), 'logo')->displayUsing(function(){
                return "<img src='".$this->logo."' style='height: 2em;'/>";
            })->asHtml(),
            Text::make(__('Data'), 'data')->onlyOnDetail()->displayUsing(function(){return $this->detail();})->asHtml()
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

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
