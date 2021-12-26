<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Http\Requests\NovaRequest;
use Vyuldashev\NovaPermission\PermissionBooleanGroup;
use Vyuldashev\NovaPermission\RoleBooleanGroup;
class Sales extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';
    public static $priority = 3;  
    public static function group()
    {
        return __("Sale");
    }
    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'nickname', 'mobile',
    ];
    public static $with = ['store'];

    public static function label()
    {
        return __('Sales');
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
            ID::make()->sortable(),
            Text::make(__('Realname'), 'name')->sortable()->rules('required', 'max:255'),
            // Text::make(__('PPV'), 'ppv')->nullable(),
            // Text::make(__('GPV'), 'gpv')->nullable(),
            // Text::make(__('TGPV'), 'tgpv')->nullable(),
            $this->moneyfield(__('TGPV'), 'tgpv')->sortable(),
            // Text::make(__('PGPV'), 'pgpv')->nullable(),
            // Text::make(__('AGPV'), 'agpv')->nullable(),
            // Text::make(__('Income').__('Ratio'), 'income_ratio')->nullable()->sortable()->displayUsing(function($v){return "$v%";}),
            // $this->moneyfield(__('Retail Income'), 'retail_income')->sortable(),
            // $this->moneyfield(__('Level Bonus'), 'level_bonus')->sortable(),
            // $this->moneyfield(__('Leader Bonus'), 'leader_bonus')->sortable(),
            // $this->moneyfield(__('Total Income'), 'total_income')->sortable(),
            Boolean::make(__('DD'), 'dd')->sortable(),
            Select::make(__('Apply').__('Status'), 'apply_status')->options(\App\Models\User::applyOptions())->displayUsingLabels(),
            BelongsTo::make(__('Senior'), 'senior', Sales::class)->nullable(),    
            Text::make(__('Org Chart'), '-')->displayUsing(function(){
                return "<a class='no-underline font-bold dim text-danger' href='/sales/$this->id/relation' target=_blank>".__('Org Chart')."</a>";
            })->asHtml(),
            HasMany::make(__("Junior"), 'juniors', Sales::class),
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
        if (!$request->user()->can(__('Action').__('User'))) {
            return [];
        }
        return [
            (new Actions\SalesGrant)->canRun(function(){return 1;}),
            (new Actions\SalesReject)->canRun(function(){return 1;}),
        ];
    }
    
    public static function indexQuery(NovaRequest $request, $query)
    {
        return self::storeQuery($request, $query)->whereNotNull('apply_status');
    }
}
