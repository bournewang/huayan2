<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Address extends Resource
{
    public static $model = \App\Models\Address::class;
    public static $title = 'id';
    public static $with = ['user', 'store'];
    public static $search = [
        'id', 'consignee', 'telephone'
    ];
    
    public static function label()
    {
        return __("Address");
    }
    public static function group()
    {
        return __("Mall");
    }
    public static function icon()
    {
        return view("nova::svg.".strtolower(explode('\\', self::class)[2]));
    }
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
            BelongsTo::make(__('User'), 'user', User::class),
            Text::make(__('Consignee'), 'consignee'),
            Text::make(__('Telephone'), 'telephone'),
            Text::make(__('Province'), 'province_id')->displayUsing(function(){return $this->province->name;}),
            Text::make(__('City'), 'city_id')->displayUsing(function(){return $this->city->name;}),
            Text::make(__('County'), 'district')->displayUsing(function(){return $this->district->name;}),
            // Text::make(__('Province'), 'province'),
            // Text::make(__('City'), 'city'),
            // Text::make(__('County'), 'county'),
            Text::make(__('Street'), 'street'),
            Boolean::make(__('Default'), 'default')
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
    public function detachedFilters(Request $request)
    {
        return [
            new Filters\ProvinceFilter,
            new Filters\CityFilter,
            new Filters\DistrictFilter,            
        ];
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
