<?php

namespace Backpack\ActivityLog;

use Backpack\ActivityLog\Observers\ActivityObserver;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;

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
        // set activity log observer
        $this->booted(fn() => Activity::observe(ActivityObserver::class));
    }
}
