<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Hubertnnn\LaravelNova\Fields\DynamicSelect\DynamicSelect;
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
            Text::make(__("Name"), 'name'),
            Text::make(__("Telephone"), 'telephone'),
            DynamicSelect::make(__('Province'), 'province_id')
                ->options(Province::pluck('name', 'id')->all())
                ->displayUsing(function(){return $this->province ? $this->province->name : '';})
                ->sortable()
                ->onlyOnForms()
                ->nullable()
                ,
            DynamicSelect::make(__('City'), 'city_id')
                ->dependsOn(['province_id'])
                ->displayUsing(function(){return $this->city ? $this->city->name : '';})
                ->options(function($values) {
                    if(isset($values['province_id']) && $province_id = $values['province_id']) {
                        $query = City::where('province_id', $province_id);
                    } else {
                        $query = City::whereNotNull('id');
                    }
                    return $query->pluck('name', 'id')->all();
                })
                ->onlyOnForms()
                ->sortable()
                ->nullable()
                ,
            DynamicSelect::make(__('District'), 'district_id')
                ->displayUsing(function(){return $this->district ? $this->district->name : '';})
                ->dependsOn(['city_id'])
                ->options(function($values) {
                    if(isset($values['city_id']) && $city_id = $values['city_id']) {
                        $query = District::where('city_id', $city_id);
                    } else {
                        $query = District::whereNotNull('id');
                    }
                    return $query->pluck('name', 'id')->all();
                })
                ->onlyOnForms()
                ->sortable()
                ->nullable()
                ,
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
