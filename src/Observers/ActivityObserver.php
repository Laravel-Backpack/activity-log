<?php

namespace Backpack\ActivityLog\Observers;

use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class ActivityObserver
{
    /**
     * Handle the Activity "creating" event.
     */
    public function creating(Activity $activity): void
    {
        if (! $activity->causer_id) {
            $activity->causer()->associate(Auth::user() ?? backpack_user());
        }
    }
}
