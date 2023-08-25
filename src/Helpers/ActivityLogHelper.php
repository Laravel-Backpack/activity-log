<?php

namespace Backpack\ActivityLog\Helpers;

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
    public function getButtonUrl(Model $model, string $key): string
    {
        $class = $model->getMorphClass();

        $query = [
            "{$key}_model" => $class,
        ];

        if ($model->id) {
            $query = [
                ...$query,
                $key => join(',', [
                    $class,
                    $model->id,
                ]),
                "{$key}_text" => $model->{$model->identifiableAttribute()} ?? '',
            ];
        }

        return backpack_url('activity-log/?'.http_build_query($query));
    }

    /**
     * Generates Subject button helper
     *
     * @param Model $model
     * @param boolean $isEntry
     * @return string
     */
    public function getSubjectButtonUrl(Model $model): string
    {
        return $this->getButtonUrl($model, 'subject');
    }

    /**
     * Generates Causer button helper
     *
     * @param Model $model
     * @param boolean $isEntry
     * @return string
     */
    public function getCauserButtonUrl(Model $model): string
    {
        return $this->getButtonUrl($model, 'causer');
    }
}
