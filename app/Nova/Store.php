<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Select;
use OptimistDigital\NovaSimpleRepeatable\SimpleRepeatable;
use WesselPerik\StatusField\StatusField;
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
            Text::make(__('Store Name'), 'name')->sortable()->rules('required', 'max:255'),
            Text::make(__('Company Name'), 'company_name')->rules('required', 'max:255'),
            Text::make(__('License No'), 'license_no')->rules('required', 'max:255'),
            Text::make(__('Account No'), 'account_no')->rules('required', 'max:255'),
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
            SimpleRepeatable::make(__('Profit Sharing'), 'profit_sharing', [
                Select::make(__('Role'), 'role')->options(\App\Models\User::sharingRoleOptions())->displayUsingLabels(),
                Number::make(__('Sharing Ratio'), 'sharing_ratio')
                    ->min(1)->max(100)
                    ->displayUsing(function($v){return $v.'%';})
                    ->help('填写1-100之间的整数，最小为1，最大为100')
            ]),
            HasMany::make(__('Device'), 'devices', Device::class),
            HasMany::make(__('Service Order'), 'serviceOrders', ServiceOrder::class)
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
}
