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
use Pdmfc\NovaFields\ActionButton;
use App\Helpers\StoreHelper;
use OptimistDigital\NovaDetachedFilters\NovaDetachedFilters;
use Jubeki\Nova\Cards\Linkable\Linkable;
use Jubeki\Nova\Cards\Linkable\LinkableAway;
use Jubeki\Nova\Cards\Linkable\LinkableRouter;

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
        if ($rel) {
            return [
                Text::make(__('Name'), 'name')->sortable()
            ];
        }
        return [
            ID::make()->sortable(),
            BelongsTo::make(__('Category'), 'category', Category::class)->sortable()->rules('required'),
            BelongsTo::make(__('Supplier'), 'supplier', Supplier::class)->sortable(),
            Text::make(__('Name'), 'name')->sortable()->rules('required', 'max:255')
                    ->creationRules("unique:goods,name,NULL,id")
                    ->updateRules("unique:goods,name,{{resourceId}},id")
                    ,
            Text::make(__('Stock'), 'qty')->sortable()->rules('required', 'max:255'),
            Text::make(__('Brand'), 'brand')->sortable()->nullable()->hideFromIndex(),
            $this->money(__('Price'), 'price')->sortable()->nullable(),
            Select::make(__('Status'), 'status')->options((new \App\Models\Category)->statusOptions())->onlyOnForms(),
            Text::make(__('Status'))->displayUsing(function(){return $this->statusRichLabel();})->asHtml()->exceptOnForms(),
            ActionButton::make(__('Add To Cart'))->action(Actions\AddToCart::class, $this->id)->text(__('Add To Cart')),
            $this->mediaField(__('Main'), 'main'),
            $this->mediaField(__('Detail'), 'detail'),
        ];
    }

    public function cards(Request $request)
    {
        $cart = $request->user()->getCart();
        $brief = [];
        foreach ($cart->goods as $good){
            $brief[] = $good->name . 'x' . $good->pivot->quantity . ' ' . $good->pivot->subtotal;
        }
        return [
            (new NovaDetachedFilters($this->detachedFilters($request)))->withReset()->width('1/3'),
            (new LinkableRouter)
            ->title(__('Cart'))
            ->url('{"name": "detail", "params": {"resourceName": "carts", "resourceId": '.$cart->id.'}}')
            // ->subtitle( . "件商品")
            // ->width('1/3 ')
            // ->withMeta(['class' => 'linkable']),
            
        ];
    }  
    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function detachedFilters(Request $request)
    {
        return [
            new Filters\CategoryFilter,
            new Filters\SupplierFilter
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
        if ($request->input('viaRelationship')) return [];
        return [
            new Actions\Recommend,
            new Actions\OnShelf,
            new Actions\OffShelf,
            $this->actionButton(new Actions\AddToCart, 'Ordering', $request),
            new Actions\ImportGoods
        ];
    }
    
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query;
    }
}
