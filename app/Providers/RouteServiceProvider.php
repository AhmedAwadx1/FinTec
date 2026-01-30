<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    
    protected $namespace = 'App\Http\Controllers';
    protected $adminNamespace = 'App\Http\Controllers\Admin';
    protected $siteNamespace  = 'App\Http\Controllers\Site';
    protected $apiNamespace  = 'App\Http\Controllers\Api';

    public const HOME = '/home';
    
    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
        $this->mapWebSiteRoutes();
    }

    protected function mapWebRoutes()
    {
        Route::prefix('admin')
            ->middleware(['web' , 'web-cors', 'admin-lang'])
            ->namespace($this->adminNamespace)
            ->group(base_path('routes/web.php'));
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware(['api', 'api-cors', 'api-lang'])
            ->namespace($this->apiNamespace)
            ->group(base_path('routes/api.php'));
    }


    protected function mapWebSiteRoutes()
    {
        Route::middleware(['web', 'HtmlMinifier'])
            ->namespace($this->siteNamespace)
            ->group(base_path('routes/site.php'));
    }

}
