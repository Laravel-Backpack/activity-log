<?php

use Backpack\ActivityLog\Http\Controllers\ActivityLogCrudController;

/*
|--------------------------------------------------------------------------
| Backpack\ActivityLog Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are
| handled by the Backpack\ActivityLog package.
|
*/
Route::group([
    'namespace' => 'Backpack\ActivityLog\Http\Controllers',
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
], function () {
    Route::crud('activity-log', 'ActivityLogCrudController');
    Route::get('activity-log/causer', [ActivityLogCrudController::class, 'getCauserOptions']);
    Route::get('activity-log/subject', [ActivityLogCrudController::class, 'getSubjectOptions']);
});
