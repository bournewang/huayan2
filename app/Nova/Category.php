<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use App\Helpers\StoreHelper;

class Category extends Resource
{
    public static $model = \App\Category::class;
    public static $title = 'name';
    public static $with = ['parent'];
    public static $search = [
        'id', 'name', 
    ];

    public static function label()
    {
        return __('Category');
    }
    
    public static function group()
    {
        return __("Mall");
    }
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $categories = StoreHelper::relationIds(1, 'categories');
        $recommends = StoreHelper::relationIds(1, 'categories', 'recommend', 1);
        // $goods = StoreHelper::goodsIds(1);
        return [
            // Text::make(__('id'), 'id')->rules('required'),
            ID::make(),
            BelongsTo::make(__('Parent').__('Category'), 'parent', Category::class)->nullable(),
            Text::make(__('Name'), 'name')->rules('required', 'max:255'),
            Image::make(__('Image'), 'image')->maxWidth(100),
            Number::make(__('Commission'), 'commission')->nullable()->sortable(),
            Boolean::make(__('Start Selling'))->displayUsing(function()use($categories){
                return in_array($this->id, $categories);
            })->exceptOnForms(),
            Boolean::make(__('Recommend'))->displayUsing(function()use($recommends){
                return in_array($this->id, $recommends);
            })->exceptOnForms(),
            HasMany::make(__('Goods'), 'goods', Goods::class),
            // BelongsToMany::make(__('Store'), 'stores', Store::class),
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
        if (!$request->user()->can(__('Action'). __('Category'))) {
            return [];
        }
        return [
            (new Actions\AddCategory)->canRun(function(){return 1;}),
            (new Actions\RemoveCategory)->canRun(function(){return 1;}),
            (new Actions\UpdatePivot(__('Recommend'), 'categories', 'recommend', 1))->canRun(function(){return 1;}),
            (new Actions\UpdatePivot(__('Derecommend'), 'categories', 'recommend', 0))->canRun(function(){return 1;}),
            (new Actions\ImportImage)->canRun(function(){return 1;}),
        ];
    }
    
    public static function indexQuery(NovaRequest $request, $query)
    {
        // $cids = $request->user()->store->categories()->pluck('id')->all();
        // $query->whereIn('id', $cids);
        return $query;
    }
}
