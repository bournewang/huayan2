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
        // 'App\Model' => 'App\Policies\ModelPolicy',
        'App\Address' 		    => 'App\Policies\RootPolicy',
        'App\Banner' 		    => 'App\Policies\BannerPolicy',
        'App\Cart' 		        => 'App\Policies\RootPolicy',
        'App\Category' 		    => 'App\Policies\CategoryPolicy',
        'App\Goods'             => 'App\Policies\GoodsPolicy',
        'App\Order' 		    => 'App\Policies\OrderPolicy',
        'App\Revenue' 		    => 'App\Policies\RevenuePolicy',
        'App\Store' 		    => 'App\Policies\StorePolicy',
        'App\User'              => 'App\Policies\UserPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
