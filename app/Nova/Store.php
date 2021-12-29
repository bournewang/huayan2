<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
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
            // Text::make(__('License Img'), 'license_img')->nullable(),
            $this->addressFields(),
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
                    ->exceptOnForms(),
            HasMany::make(__('Device'), 'devices', Device::class)        
            // Text::make(__('Devices'))->onlyOnDetail()->displayUsing(function(){
            //     $list = json_decode($this->devices);
            //     $str = "";
            //     foreach ($list as $item) {
            //         $product_key = $item->attributes->type;
            //         $devices = explode("\n", $item->attributes->device_list);
            //         if (count($devices) < 1)continue;
            //         \Log::debug($devices);
            //         $str .= $product_key .':'."<br/>";//. implode('|', $devices) . "<br/>";
            //         $c = new \App\Iot\Device($product_key, $devices);
            //         $res = null;
            //         $res = $c->batchStatus();
            //         \Log::debug("===res: ");
            //         \Log::debug($res);
            // 
            //         $status = [];
            //         if (!$res) continue;
            //         foreach ($res['DeviceStatusList']['DeviceStatus'] as $d){
            //             $status[$d['DeviceName']] = $d['Status'];
            //             $str .= $d['DeviceName'] . " " . $d['Status'];
            //             if ($d['Status'] == 'ONLINE') {
            //                 $str .= "<span class='text text-success'>".view('nova::svg.play')."</span>";
            //             }else{
            //                 $str .= "<span class='text text-default'>".view('nova::svg.minus-circle') . "</span>";
            //             }
            //             $str .= "<br/>";
            //         }
            //     }
            //     return $str;
            // })->asHtml()
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
            new Actions\Activate,
            new Actions\Deactivate
        ];
    }
    
    public static function indexQuery(NovaRequest $request, $query)
    {
        return self::storeQuery($request, $query, 'id');
    }
}
