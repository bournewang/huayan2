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
use Laravel\Nova\Fields\BelongsToMany;
use App\Store as StoreModel;
use App\Helpers\StoreHelper;
class Goods extends Resource
{
    public static $model = \App\Goods::class;
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
    
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $rel = !!$request->input('viaRelationship');
        $goods      = StoreHelper::relationIds(1, 'goods');
        $hots       = StoreHelper::relationIds(1, 'goods', 'hot', 1);
        $recommends = StoreHelper::relationIds(1, 'goods', 'recommend', 1);

        return [
            ID::make()->sortable(),
            // Text::make(__('shopId'), 'shopId'),
            BelongsTo::make(__('Category'), 'category', Category::class)->sortable()->rules('required'),
            Image::make(__('Image'), 'img')->nullable()->thumbnail(function($v){return \Storage::url($this->img_s);})->preview(function(){return \Storage::url($this->img_m);}),
            Text::make(__('Name'), 'name')->sortable()->rules('required', 'max:255'),
            Text::make(__('Stock'), 'qty')->sortable()->rules('required', 'max:255')->canSee(function()use($rel){return !$rel;}),
            Text::make(__('Type'), 'type')->sortable()->nullable()->canSee(function()use($rel){return !$rel;})->hideFromIndex(),
            Text::make(__('Brand'), 'brand')->sortable()->nullable()->canSee(function()use($rel){return !$rel;})->hideFromIndex(),
            Text::make(__('saleFlag'), 'saleFlag')->sortable()->nullable()->canSee(function()use($rel){return !$rel;})->hideFromIndex(),
            
            Text::make(__('Price'), 'price')->sortable()->nullable(),
            Text::make(__('pv'), 'pv')->nullable()->canSee(function()use($rel){return !$rel;})->hideFromIndex(),
            Text::make(__('saleCount'), 'saleCount')->nullable()->canSee(function()use($rel){return !$rel;}),
            Text::make(__('customs_id'), 'customs_id')->nullable()->canSee(function()use($rel){return !$rel;})->hideFromIndex(),
            Number::make(__('Commission'), 'commission')->nullable()->sortable()->hideFromIndex(),
            Text::make(__('Detail'), 'detail')->nullable()->asHtml()->hideFromIndex()->hideFromIndex(),
            Boolean::make(__('Start Selling'))->displayUsing(function()use($goods){
                return in_array($this->id, $goods);
            })->exceptOnForms(),
            Boolean::make(__('Recommend'))->displayUsing(function()use($recommends){
                return in_array($this->id, $recommends);
            })->exceptOnForms(),
            // Boolean::make(__('Hot'))->displayUsing(function()use($hots){
            //     return in_array($this->id, $hots);
            // }),
            BelongsToMany::make(__('Cart'), 'carts', Cart::class)->fields(new Fields\CartItemFields)
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
        if (!$request->user()->can(__('Action'). __('Goods'))) {
            return [];
        }
        return [
            (new Actions\StartSelling)->canRun(function(){ return 1;}),
            (new Actions\StopSelling)->canRun(function(){ return 1;}),
            (new Actions\UpdatePivot(__('Recommend'), 'goods', 'recommend', 1))->canRun(function(){ return 1;}),
            (new Actions\UpdatePivot(__('Derecommend'), 'goods', 'recommend', 0))->canRun(function(){ return 1;}),
            // new Actions\UpdatePivot(__('Hoting'), 'goods', 'hot', 1),
            // new Actions\UpdatePivot(__('Dehoting'), 'goods', 'hot', 0),
        ];
    }
    
    public static function indexQuery(NovaRequest $request, $query)
    {
        $cids = StoreModel::find(1)->categories()->pluck('id')->all();
        $query->whereIn('category_id', $cids);
        return $query;
    }
}
