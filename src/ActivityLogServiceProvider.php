<?php

namespace Backpack\ActivityLog;

use Illuminate\Support\ServiceProvider;

class ActivityLogServiceProvider extends ServiceProvider
{
    use AutomaticServiceProvider;

    protected $vendorName = 'backpack';
    protected $packageName = 'activity-log';
    protected $commands = [];
}
