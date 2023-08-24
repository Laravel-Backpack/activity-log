<?php

namespace Backpack\ActivityLog\Http\Controllers\Operations;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait ShowEntryActivityLogsOperation
{
    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupShowEntryLogsActivityOperationDefaults(): void
    {
        CRUD::allowAccess('logsActivityOperation');

        CRUD::operation('list', function () {
            CRUD::addButton('line', 'view_entry_logs', 'view', 'backpack.activity-log::buttons.view_entry_logs');
        });
    }
}
