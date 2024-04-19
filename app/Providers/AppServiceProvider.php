<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Setting;
use App\Models\Shomoos;
use App\Observers\UserObserver;
use App\Observers\shomoosObserver;
use Spatie\Multitenancy\Models\Tenant;
use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\Channels\DatabaseChannel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('settings', function () {
            $settings = Setting::first();
            return $settings;
        });
        $this->app->singleton('hotel_date', function () {
            $settings = Setting::get('hotel_date');
            return $settings;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */


    public function boot()
    {
        Shomoos::observe(shomoosObserver::class);

    //    $this->app->instance(BaseDatabaseChannel::class, new DatabaseChannel());
        
    //     public function boot()
    //     {

     //   $tenant = Tenant::current();
    //     $settings = Setting::get();
    //    // config(['settings_'.$tenant->id => $settings]);
    //     cache()->put('settings_'.$tenant->id , $settings);

            $tenant = Tenant::current();
            if($tenant)
            {
                $settings = Setting::get();
//                 config(['settings_'.$tenant->id => $settings]);
                 cache()->put('settings_'.$tenant->id , $settings);
            }
        


    //     }
     }


}
