<?php

namespace Backpack\ActivityLog\Traits;

use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Facades\CauserResolver;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity as OriginalLogsActivity;

trait LogsActivity
{
    use OriginalLogsActivity {
        bootLogsActivity as originalBootLogsActivity;
    }

    /**
     * Override the boot method to set the causer
     *
     * @return void
     */
    protected static function bootLogsActivity(): void
    {
        CauserResolver::setCauser(backpack_user() ?? Auth::user());

        self::originalBootLogsActivity();
    }

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
