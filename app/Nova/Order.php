<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;

class Order extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'orderNo';

    public static function label()
    {
        return __("Order");
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
            BelongsTo::make(__('Store'), 'store', Store::class),
            BelongsTo::make(__('User'), 'user', User::class),
            Text::make(__('orderNo'), 'orderNo'),
            Text::make(__('payNo'), 'payNo')->nullable(),
            Text::make(__('orderAmount'), 'orderAmount'),
            Text::make(__('orderTime'), 'orderTime')->hideFromIndex(),
            Text::make(__('payTime'), 'payTime')->nullable()->hideFromIndex(),
            Text::make(__('buyerRegNo'), 'buyerRegNo')->nullable(),
            Text::make(__('buyerName'), 'buyerName')->nullable(),
            Text::make(__('buyerTelephone'), 'buyerTelephone')->nullable()->hideFromIndex(),
            Text::make(__('buyerIdNumber'), 'buyerIdNumber')->nullable()->hideFromIndex(),
            Text::make(__('consignee'), 'consignee')->nullable(),
            Text::make(__('consigneeTelephone'), 'consigneeTelephone')->nullable()->hideFromIndex(),
            Text::make(__('consigneeAddress'), 'consigneeAddress')->nullable()->hideFromIndex(),
            Text::make(__('receiverProvince'), 'receiverProvince')->nullable(),
            Text::make(__('receiverCity'), 'receiverCity')->nullable(),
            Text::make(__('receiverCounty'), 'receiverCounty')->nullable()->hideFromIndex(),
            Text::make(__('payRequest'), 'payRequest')->nullable()->hideFromIndex(),
            Text::make(__('payResponse'), 'payResponse')->nullable()->hideFromIndex(),
            DateTime::make(__('Created At'),'created_at'),
            // Text::make(__('orderInfoList'), 'orderInfoList')->nullable(),
            BelongsToMany::make(__('Goods'), 'goods', Goods::class)->fields(new Fields\CartItemFields)
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
    
    public static function indexQuery(NovaRequest $request, $query)
    {
        return self::storeQuery($request, $query);
    }
}
