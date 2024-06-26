<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
	'App\Models\MembershipUsedItem' => 'App\Policies\MemershipUsedItemPolicy',
	'App\Models\DeviceRental' 	=> 'App\Policies\DeviceRentalPolicy',
	'App\Models\DeviceRental' 	=> 'App\Policies\DeviceRentalPolicy',
	    'App\Models\MembershipCard' 	=> 'App\Policies\MembershipCardPolicy',
        'App\Models\Bill'       => 'App\Policies\BillPolicy',
        'App\Models\BillItem' 	=> 'App\Policies\BillItemPolicy',
	    'App\Models\Health' 	=> 'App\Policies\HealthPolicy',
    	'App\Models\StockItem'     => 'App\Policies\StockItemPolicy',
    	'App\Models\Stock'         => 'App\Policies\StockPolicy',
        'App\Models\PurchaseOrder'  => 'App\Policies\PurchaseOrderPolicy',
        'App\Models\SalesOrder' 	=> 'App\Policies\SalesOrderPolicy',
        'App\Models\Address' 	=> 'App\Policies\AddressPolicy',
        'App\Models\Banner' 	=> 'App\Policies\BannerPolicy',
        'App\Models\Cart'   	=> 'App\Policies\CartPolicy',
        'App\Models\Category' 	=> 'App\Policies\CategoryPolicy',
        'App\Models\City'   	=> 'App\Policies\CityPolicy',
        'App\Models\Clerk'  	=> 'App\Policies\ClerkPolicy',
        'App\Models\Customer' 	=> 'App\Policies\CustomerPolicy',
        'App\Models\Manager'    => 'App\Policies\ManagerPolicy',
        'App\Models\Device' 	=> 'App\Policies\DevicePolicy',
        'App\Models\District' 	=> 'App\Policies\DistrictPolicy',
        'App\Models\Expert' 	=> 'App\Policies\ExpertPolicy',
        'App\Models\Goods'  	=> 'App\Policies\GoodsPolicy',
        'App\Models\Logistic' 	=> 'App\Policies\LogisticPolicy',
        'App\Models\Order'  	=> 'App\Policies\OrderPolicy',
        'App\Models\Province' 	=> 'App\Policies\ProvincePolicy',
        'App\Models\Revenue' 	=> 'App\Policies\RevenuePolicy',
        'App\Models\Review' 	=> 'App\Policies\ReviewPolicy',
        'App\Models\Salesman' 	=> 'App\Policies\SalesmanPolicy',
        'App\Models\ServiceOrder'  	=> 'App\Policies\ServiceOrderPolicy',
        'App\Models\Setting' 	=> 'App\Policies\SettingPolicy',
        'App\Models\Store'  	=> 'App\Policies\StorePolicy',
        'App\Models\Supplier' 	=> 'App\Policies\SupplierPolicy',
        'App\Models\User'   	=> 'App\Policies\UserPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            return $user->hasRole('-') ? true : null;
        });
    }
}
