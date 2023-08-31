<?php

namespace Backpack\ActivityLog\Helpers;

use Backpack\ActivityLog\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogHelper
{
    private $options = [
        ActivityLog::CAUSER => 'causer',
        ActivityLog::SUBJECT => 'subject',
    ];

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

        foreach ($this->options as $option => $key) {
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

    /**
     * Generates a button title for the views
     *
     * @param integer|null $keys
     * @return string
     */
    public function getButtonTitle(?int $keys): string
    {
        $keys ??= ActivityLog::SUBJECT;
        $key = $this->options[$keys === ActivityLog::CAUSER ? ActivityLog::CAUSER : ActivityLog::SUBJECT];

        return ucfirst(__("backpack.activity-log::activity_log.activity_log_button_$key"));
    }
}
