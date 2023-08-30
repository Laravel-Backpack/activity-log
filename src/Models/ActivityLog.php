<?php

namespace Backpack\ActivityLog\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Activity
{
    use CrudTrait;

    public const CAUSER = 1;
    public const SUBJECT = 2;
}
