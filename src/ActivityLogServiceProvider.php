<?php

namespace Backpack\ActivityLog;

use Illuminate\Support\ServiceProvider;

class ActivityLogServiceProvider extends ServiceProvider
{
    use AutomaticServiceProvider;

    protected $vendorName = 'backpack';
    protected $packageName = 'activity-log';
    protected $commands = [];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // set backpack authguard
        $this->booted(function () {
            if (! config('activitylog.default_auth_driver')) {
                config(['activitylog.default_auth_driver' => backpack_guard_name()]);
            }
        });
    }
}
