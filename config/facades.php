<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Facade Aliasing
    |--------------------------------------------------------------------------
    */

    'aliases' => Facade::defaultAliases(),

    /*
    |--------------------------------------------------------------------------
    | Application Service Providers
    |--------------------------------------------------------------------------
    */

    'providers' => ServiceProvider::defaultProviders()->merge([
        // Application service providers...
    ])->toArray(),

];
