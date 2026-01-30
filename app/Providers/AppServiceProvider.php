<?php

namespace App\Providers;

use App\Models\Social;
use App\Models\SiteSetting;
use App\Services\SettingService;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\User\UserRepository;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider {

    protected $settings;
    protected $socials;

    public function register()
    {
        // Repository bindings moved to RepositoryServiceProvider
    }

    public function boot() {
        Paginator::useBootstrap();
        Schema::defaultStringLength(191);
        $this->loadMigrationsFrom([
            database_path().'/migrations',
        ]);

        try {
            // Check if site_settings table exists before querying
            if (Schema::hasTable('site_settings')) {
                $this->settings = Cache::rememberForever('settings', function () {
                    return SettingService::appInformations(SiteSetting::pluck('value', 'key'));
                });
            } else {
                $this->settings = null;
            }

            // Check if socials table exists before querying
            if (Schema::hasTable('socials')) {
                $this->socials = Cache::rememberForever('socials', function () {
                    return Social::get();
                });
            } else {
                $this->socials = collect();
            }

        } catch (Exception $e) {
            // Silently handle exceptions - tables may not exist yet during migrations
            $this->settings = null;
            $this->socials = collect();
        }

        view()->composer('admin.*', function ($view) {
            $view->with([
                'settings' => $this->settings,
            ]);
        });

        // -------------- lang ---------------- \\
        app()->singleton('lang', function () {
            return session('lang', 'ar');
        });
        // -------------- lang ---------------- \\
    }
}
