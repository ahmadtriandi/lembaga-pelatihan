<?php

namespace App\Providers;

use App\Models\Registration;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::defaultView('admin.partials.pagination');

        // $site tersedia di semua view: $site['site_name'], $site['whatsapp_1'], dst.
        View::composer('*', function ($view) {
            static $site = null;
            if ($site === null) {
                $site = Schema::hasTable('settings') ? Setting::allCached() : [];
            }
            $view->with('site', $site);
        });

        View::composer('layouts.admin', function ($view) {
            $view->with('newRegistrations', Registration::where('status', 'baru')->count());
        });
    }
}
