<?php

namespace Backpack\ActivityLog\Helpers;

use Backpack\ActivityLog\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogHelper
{
    /**
     * Generates a button url for the views
     *
     * @param Model $entry
     * @param string $key
     * @param boolean $isEntry
     * @return string
     */
    public function getButtonUrl(Model $model, ?int $keys): string
    {
        $query = [];
        $class = $model->getMorphClass();

        $keys ??= ActivityLog::SUBJECT;

        $options = [
            ActivityLog::CAUSER => 'causer',
            ActivityLog::SUBJECT => 'subject',
        ];

        foreach ($options as $option => $key) {
            $query['combined'] = true;

            if ($keys &$option) {
                if ($model->id) {
                    $query = [
                        ...$query,
                        $key => join(',', [
                            $class,
                            $model->id,
                        ]),
                        "{$key}_text" => $model->{$model->identifiableAttribute()} ?? '',
                    ];
                } else {
                    $query["{$key}_model"] = $class;
                }
            }
        }

        return backpack_url('activity-log/?'.http_build_query($query));
    }
}
