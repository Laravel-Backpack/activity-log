<?php

namespace Backpack\ActivityLog\Helpers;

use Backpack\ActivityLog\Enums\ActivityLogEnum;
use Illuminate\Database\Eloquent\Model;

class ActivityLogHelper
{
    /**
     * Generates a button url for the views
     *
     * @param Model $entry
     * @param array<ActivityLogEnum>|ActivityLogEnum|null $keys
     * @return string
     */
    public function getButtonUrl(Model $model, array | ActivityLogEnum | null $keys): string
    {
        $query = collect($keys ?? ActivityLogEnum::SUBJECT)
            ->map(fn($key) => is_int($key) ? ActivityLogEnum::from($key) : $key)
            ->mapWithKeys(function (ActivityLogEnum $key) use ($model) {
                $class = $model->getMorphClass();
                $key = strtolower($key->name);

                return $model->id ? [
                    $key => join(',', [
                        $class,
                        $model->id,
                    ]),
                    "{$key}_text" => $model->{$model->identifiableAttribute()} ?? '',
                ] : [
                    "{$key}_model" => $class,
                ];
            })
            ->toArray();

        return backpack_url('activity-log/?'.http_build_query($query));
    }

    /**
     * Generates a button title for the views
     *
     * @param array<ActivityLogEnum>|ActivityLogEnum|null $keys
     * @return string
     */
    public function getButtonTitle(array | ActivityLogEnum | null $keys): string
    {
        $keys = collect($keys)
            ->map(fn($key) => is_int($key) ? ActivityLogEnum::from($key) : $key)
            ->toArray();

        // default to subject key
        $key = ActivityLogEnum::SUBJECT->name;

        if (in_array(ActivityLogEnum::CAUSER, $keys)) {
            $key = ActivityLogEnum::CAUSER->name;
        }

        return ucfirst(__('backpack.activity-log::activity_log.activity_log_button_'.strtolower($key)));
    }
}
