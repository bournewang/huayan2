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
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Address' 		    => 'App\Policies\RootPolicy',
        'App\Models\Banner' 		    => 'App\Policies\BannerPolicy',
        'App\Models\Cart' 		        => 'App\Policies\RootPolicy',
        'App\Models\Category' 		    => 'App\Policies\CategoryPolicy',
        'App\Models\Goods'             => 'App\Policies\GoodsPolicy',
        'App\Models\Order' 		       => 'App\Policies\OrderPolicy',
        'App\Models\Revenue' 		    => 'App\Policies\RevenuePolicy',
        'App\Models\Store' 		       => 'App\Policies\StorePolicy',
        'App\Models\User'              => 'App\Policies\UserPolicy',
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
