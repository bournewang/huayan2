<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use App\Helpers\StoreHelper;
class Goods extends Resource
{
    public static $model = \App\Models\Goods::class;
    public static $title = 'name';
    public static $with = ['category'];
    public static $search = [
        'name', 
    ];

    public static function label()
    {
        return __('Goods');
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
        $rel = !!$request->input('viaRelationship');
        // $goods      = StoreHelper::relationIds(1, 'goods');
        // $hots       = StoreHelper::relationIds(1, 'goods', 'hot', 1);
        // $recommends = StoreHelper::relationIds(1, 'goods', 'recommend', 1);

        return [
            ID::make()->sortable(),
            // Text::make(__('shopId'), 'shopId'),
            BelongsTo::make(__('Category'), 'category', Category::class)->sortable()->rules('required'),
            // Image::make(__('Image'), 'img')->nullable()->thumbnail(function($v){return \Storage::url($this->img_s);})->preview(function(){return \Storage::url($this->img_m);}),
            Text::make(__('Name'), 'name')->sortable()->rules('required', 'max:255'),
            Text::make(__('Stock'), 'qty')->sortable()->rules('required', 'max:255')->canSee(function()use($rel){return !$rel;}),
            // Text::make(__('Type'), 'type')->sortable()->nullable()->canSee(function()use($rel){return !$rel;})->hideFromIndex(),
            Text::make(__('Brand'), 'brand')->sortable()->nullable()->canSee(function()use($rel){return !$rel;})->hideFromIndex(),
            // Text::make(__('saleFlag'), 'saleFlag')->sortable()->nullable()->canSee(function()use($rel){return !$rel;})->hideFromIndex(),
            
            Text::make(__('Price'), 'price')->sortable()->nullable(),
            Text::make(__('Detail'), 'detail')->nullable()->asHtml()->hideFromIndex()->hideFromIndex(),
            Select::make(__('Status'), 'status')->options((new \App\Models\Category)->statusOptions())->onlyOnForms(),
            Text::make(__('Status'))->displayUsing(function(){return $this->statusRichLabel();})->asHtml()->exceptOnForms(),
            $this->mediaField(__('Image'), 'photo'),
            // BelongsToMany::make(__('Cart'), 'carts', Cart::class)->fields(new Fields\CartItemFields)
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
            new Filters\CategoryFilter
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
        return [
            new Actions\Recommend,
            new Actions\OnShelf,
            new Actions\OffShelf,
            // new Actions\Derecommend,
        ];
    }
}
