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
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Http\Requests\NovaRequest;
use Vyuldashev\NovaPermission\PermissionBooleanGroup;
use Vyuldashev\NovaPermission\RoleBooleanGroup;
class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\User::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'email',
    ];
    public static $with = ['store'];

    public static function label()
    {
        return __('User');
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
            BelongsTo::make(__('Store'),'store', Store::class)->nullable(),
            // Gravatar::make(__('Avatar'), 'avatar')->maxWidth(50)->default(function($val){return $val;}),
            Image::make(__('Avatar'), 'avatar')->maxWidth(50)->preview(function($val){return $val;})->thumbnail(function($val){return $val;}),

            Text::make(__('Nickname'), 'nickname'),
            Text::make(__('Realname'), 'name')->sortable()->rules('required', 'max:255'),
            Text::make(__('Province'), 'province'),
            Text::make(__('City'), 'city'),
            Text::make(__('Gender'), 'gender'),
            Text::make(__('Mobile'), 'mobile'),
            Image::make(__('QR Code'), 'qrcode'),
            // Text::make(__('Email'), 'email')
            //     ->sortable()
            //     ->rules('required', 'email', 'max:254')
            //     ->creationRules('unique:users,email')
            //     ->updateRules('unique:users,email,{{resourceId}}'),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),
            // Text::make(__('PPV'), 'ppv')->nullable(),
            // Text::make(__('GPV'), 'gpv')->nullable(),
            // Text::make(__('TGPV'), 'tgpv')->nullable(),
            // Text::make(__('PGPV'), 'pgpv')->nullable(),
            // Text::make(__('AGPV'), 'agpv')->nullable(),
            // Text::make(__('Income').__('Ratio'), 'income_ratio')->nullable()->displayUsing(function($v){return "$v%";}),
            // Text::make(__('Income'), 'income')->nullable(),
            // Boolean::make(__('Direct Distributor'), 'dd'),
            // BelongsTo::make(__('Senior'), 'senior', User::class)->nullable(),    
            // Text::make(__('Sharing Ratio'), 'superior')->displayUsing(function(){
            //     return $this->superiorsToString();
            // })->asHtml()->exceptOnForms(),
            $this->mediaField(__('ID'), 'id'),
            RoleBooleanGroup::make(__('Roles'), 'roles'),
            // PermissionBooleanGroup::make('Permissions'),
            HasMany::make(__("Address"), 'addresses', Address::class),
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
