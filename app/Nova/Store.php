<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Comodolab\Nova\Fields\Help\Help;
use WesselPerik\StatusField\StatusField;
// use Eminiarts\Tabs\Tabs;
// use Eminiarts\Tabs\Tab;
// use Eminiarts\Tabs\TabsOnEdit;
use NovaAjaxSelect\AjaxSelect;
use App\Models\Province;
class Store extends Resource
{
    public static $model = \App\Models\Store::class;
    public static $title = 'name';
    public static $search = [
        'name', 
    ];
    public static function label()
    {
        return __('Store');
    }
    public static function group()
    {
        return __("Chain Store");
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
        $user = $request->user();
        return [
            // Tabs::make(__('Store') . __('Detail'), [
                // Tab::make('Info', [    
            // ID::make(),
            Text::make(__('Store Name'), 'name')->sortable()->rules('required', 'max:255'),
            Text::make(__('Company Name'), 'company_name')->rules('required', 'max:255'),
            Text::make(__('License No'), 'license_no')->rules('required', 'max:255'),
            Text::make(__('Account No'), 'account_no')->rules('required', 'max:255'),
            Text::make(__('Contact'), 'contact')->nullable(),
            Text::make(__('Telephone'), 'telephone')->nullable(),
            // Text::make(__('License Img'), 'license_img')->nullable(),
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
            $this->mediaField(__('License'), 'license'),
            $this->mediaField(__('Photo'), 'photo'),
            
            // Select::make(__('Status'), 'status')->options((new \App\Models\Store)->statusOptions())->onlyOnForms(),
            StatusField::make(__('Status'), 'status')
                    ->values([
                        'inactive'  => $this->inactive == $this->status,
                        'pending'   => $this->pending == $this->status,
                        'active'    => $this->active == $this->status
                    ])
                    ->info($this->statusLabel())
                    ->exceptOnForms()            
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
        return [
            new Actions\Active,
            new Actions\Inactive
        ];
    }
    
    public static function indexQuery(NovaRequest $request, $query)
    {
        return self::storeQuery($request, $query, 'id');
    }
}
