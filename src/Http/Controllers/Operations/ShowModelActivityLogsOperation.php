<?php

namespace Backpack\ActivityLog\Http\Controllers\Operations;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait ShowModelActivityLogsOperation
{
    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupShowModelLogsActivityOperationDefaults(): void
    {
        CRUD::allowAccess('logsActivityOperation');

        CRUD::operation(['list', 'show'], function () {
            CRUD::addButton('top', 'view_subject_model_logs', 'view', 'backpack.activity-log::buttons.view_subject_model_logs');
        });
    }
}