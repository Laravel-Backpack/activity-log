<?php

namespace Backpack\ActivityLog;

use Illuminate\Support\ServiceProvider;

class ActivityLogServiceProvider extends ServiceProvider
{
    use AutomaticServiceProvider;

    protected $vendorName = 'backpack';
    protected $packageName = 'activity-log';
    protected $commands = [];

    public function boot(): void
    {
        $this->autoboot();

        // tell Backpack to automatically check the COLUMNS directory in this package
        // app()->config['backpack.crud.view_namespaces.columns'] = (function () {
        //     $fieldNamespaces = config('backpack.crud.view_namespaces.columns');
        //     $fieldNamespaces[] = $this->vendorName.'.'.$this->packageName.'::columns';

        //     return $fieldNamespaces;
        // })();
    }
}
