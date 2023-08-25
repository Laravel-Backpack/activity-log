<?php

namespace Backpack\ActivityLog\Traits;

use Spatie\Activitylog\LogOptions;

trait LogsActivity
{
    /**
     * Spatie Log Options
     * By default will log only the changes between fillables
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }
}
