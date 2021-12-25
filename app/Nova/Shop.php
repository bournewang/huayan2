<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use NovaAjaxSelect\AjaxSelect;
use OptimistDigital\NovaDetachedFilters\NovaDetachedFilters;
use App\Province;
use App\City;
use App\District;

class Shop extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Shop::class;

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
        'id', 'name', 'telephone', 'street'
    ];

    public static function label()
    {
        return __('Shop');
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
            Text::make(__("Name"), 'name'),
            Text::make(__("Telephone"), 'telephone'),
            Select::make(__('Province'), 'province_id')
                ->options(Province::pluck('name', 'id')->all())
                ->displayUsingLabels()
                ->onlyOnForms(),
            
            AjaxSelect::make(__('City'), 'city_id')
                ->get('/api/provinces/{province_id}/cities')
                ->parent('province_id')
                ->onlyOnForms(),

            AjaxSelect::make(__('District'), 'district_id')
                ->get('/api/cities/{city_id}/districts')
                ->parent('city_id')
                ->onlyOnForms(),
            
            Text::make(__('Street'), 'street')->onlyOnForms(),  
            Text::make(__('Address'), 'address')->displayUsing(function(){return $this->display_address();})->exceptOnForms(),
            $this->mediaField(__('Contract'), 'contract'),
            $this->mediaField(__('Photo'), 'photo'),
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
