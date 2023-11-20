<?php

namespace Backpack\ActivityLog\Http\Controllers;

use Backpack\ActivityLog\Models\ActivityLog;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class ActivityLogCrudController
 * @package Backpack\ActivityLog\Http\Controllers
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ActivityLogCrudController extends CrudController
{
    const CAUSER = 'causer';
    const SUBJECT = 'subject';
    const KNOWN_EVENTS = ['created', 'updated', 'deleted', 'restored'];

    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(ActivityLog::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/activity-log');
        CRUD::setEntityNameStrings(__('backpack.activity-log::activity_log.activity_log'), __('backpack.activity-log::activity_log.activity_logs'));
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn([
            'name' => 'causer_type',
            'label' => ucfirst(__('backpack.activity-log::activity_log.causer_model')),
            'type' => 'text',
            'value' => fn ($entry) => $entry->causer ? Str::of(get_class($entry->causer))->afterLast('\\') : '',
            'wrapper' => [
                'title' => fn ($crud, $column, $entry) => $entry->causer ? get_class($entry->causer) : '',
            ],
        ]);

        CRUD::addColumn([
            'name' => 'causer',
            'label' => ucfirst(__('backpack.activity-log::activity_log.causer')),
            'type' => 'text',
            'value' => fn($entry) => $entry->causer && method_exists($entry->causer, 'identifiableAttribute') ? $entry->causer->{$entry->causer->identifiableAttribute()} : '',
            'wrapper' => [
                'href' => fn($crud, $column, $entry) => $this->getEntryUrl($entry->causer) ?? '',
                'element' => fn($crud, $column, $entry) => $this->getEntryUrl($entry->causer) ? 'a' : 'span',
                'title' => function ($crud, $column, $entry) {
                    return $entry->causer ? "ID {$entry->causer->getKey()}" : '';
                },
            ],
        ]);

        CRUD::addColumn([
            'name' => 'event',
            'label' => ucfirst(__('backpack.activity-log::activity_log.event')),
            'type' => 'text',
            'value' => function ($entry) {
                if (in_array($entry->event, self::KNOWN_EVENTS)) {
                    $entry->event = "backpack.activity-log::activity_log.{$entry->event}";
                }
                return ucfirst(__($entry->event));
            },
        ]);

        CRUD::addColumn([
            'name' => 'subject_type',
            'label' => ucfirst(__('backpack.activity-log::activity_log.subject_model')),
            'type' => 'text',
            'value' => fn($entry) => $entry->subject ? Str::of(get_class($entry->subject))->afterLast('\\') : '',
            'wrapper' => [
                'title' => fn($crud, $column, $entry) => $entry->subject ? get_class($entry->subject) : '',
            ],
        ]);

        CRUD::addColumn([
            'name' => 'subject',
            'label' => ucfirst(__('backpack.activity-log::activity_log.subject')),
            'type' => 'text',
            'value' => fn($entry) => $entry->subject && method_exists($entry->subject, 'identifiableAttribute') ? $entry->subject->{$entry->subject->identifiableAttribute()} : '',
            'wrapper' => [
                'href' => fn($crud, $column, $entry) => $this->getEntryUrl($entry->subject) ?? '',
                'element' => fn($crud, $column, $entry) => $this->getEntryUrl($entry->subject) ? 'a' : 'span',
                'title' => function ($crud, $column, $entry) {
                    return $entry->causer ? "ID {$entry->causer->getKey()}" : '';
                },
            ],
        ]);

        CRUD::addColumn([
            'name' => 'created_at',
            'label' => ucfirst(__('backpack.activity-log::activity_log.date')),
            'type' => 'datetime',
        ]);

        // Filters
        $this->setupFilters();
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();

        CRUD::set('show.contentClass', 'col-md-12');

        CRUD::addColumn([
            'name' => 'causer_type',
            'label' => ucfirst(__('backpack.activity-log::activity_log.causer_model')),
            'type' => 'text',
            'wrapper' => [
                'class' => 'bg-blue',
                'element' => 'kbd',
            ],
        ])->beforeColumn('causer');

        CRUD::setColumnDetails('subject_type', [
            'value' => fn($entry) => $entry->subject ? get_class($entry->subject) : '',
            'wrapper' => [
                'class' => 'bg-blue',
                'element' => 'kbd',
            ],
        ]);

        CRUD::addColumn([
            'name' => 'properties',
            'type' => 'activity_changes',
            'label' => ucfirst(__('backpack.activity-log::activity_log.changes')),
            'escaped' => false,
        ]);
    }

    /**
     * Setup Filters
     *
     * @return void
     */
    public function setupFilters()
    {
        if(! backpack_pro()) {
            return;
        }
        
        /**
         * Causer Model
         */
        CRUD::addFilter([
            'name' => 'causer_model',
            'type' => 'select2',
            'label' => ucfirst(__('backpack.activity-log::activity_log.causer_model')),
        ], function () {
            return ActivityLog::select('causer_type')
                ->distinct()
                ->pluck('causer_type', 'causer_type')
                ->map(fn($entry) => Str::of($entry)->afterLast('\\')->ucfirst())
                ->toArray();
        }, function ($value) {
            CRUD::addClause('where', 'causer_type', $value);
        });

        /**
         * Causer Entry
         */
        CRUD::addFilter([
            'name' => 'causer',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('backpack.activity-log::activity_log.causer')),
        ],
            backpack_url('activity-log/causer'),
            function ($value) {
                [$type, $id] = explode(',', $value);
                CRUD::addClause('where', 'causer_type', $type);
                CRUD::addClause('where', 'causer_id', $id);
            });

        /**
         * Event
         */
        CRUD::addFilter([
            'name' => 'event',
            'type' => 'select2',
            'label' => ucfirst(__('backpack.activity-log::activity_log.event')),
        ], function () {
            return ActivityLog::select('event')
                ->distinct()
                ->pluck('event', 'event')
                ->map(fn ($entry) => ucfirst(__($entry)))
                ->toArray();
        }, function ($value) {
            CRUD::addClause('where', 'event', $value);
        });

        /**
         * Subject Model
         */
        CRUD::addFilter([
            'name' => 'subject_model',
            'type' => 'select2',
            'label' => ucfirst(__('backpack.activity-log::activity_log.subject_model')),
        ], function () {
            return ActivityLog::select('subject_type')
                ->distinct()
                ->pluck('subject_type', 'subject_type')
                ->map(fn($entry) => Str::of($entry)->afterLast('\\')->ucfirst())
                ->toArray();
        }, function ($value) {
            CRUD::addClause('where', 'subject_type', $value);
        });

        /**
         * Subject Entry
         */
        CRUD::addFilter([
            'name' => 'subject',
            'type' => 'select2_ajax',
            'label' => ucfirst(__('backpack.activity-log::activity_log.subject')),
        ],
            backpack_url('activity-log/subject'),
            function ($value) {
                [$type, $id] = explode(',', $value);
                CRUD::addClause('where', 'subject_type', $type);
                CRUD::addClause('where', 'subject_id', $id);
            });

        /**
         * Date Range
         */
        CRUD::addFilter([
            'type' => 'date_range',
            'name' => 'date_range',
            'label' => ucfirst(__('backpack.activity-log::activity_log.date')),
        ],
            false,
            function ($value) {
                $dates = json_decode($value);
                CRUD::addClause('where', 'created_at', '>=', $dates->from);
                CRUD::addClause('where', 'created_at', '<=', $dates->to.' 23:59:59');
            });
    }

    /**
     * Try to guess the entry url route
     *
     * @param Model $entry
     * @return null|string
     */
    public function getEntryUrl(Model | null $entry): null | string
    {
        if (! $entry) {
            return null;
        }

        try {
            $entity = Str::of($entry->getTable())->singular();
            return url(route("$entity.show", ['id' => $entry->getKey()]));
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Get Causer Options for filters
     *
     * @param Request $request
     * @return array
     */
    public function getCauserOptions(Request $request): array
    {
        return $this->getMorphOptions($request, self::CAUSER);
    }

    /**
     * Get Subject Options for filters
     *
     * @param Request $request
     * @return array
     */
    public function getSubjectOptions(Request $request): array
    {
        return $this->getMorphOptions($request, self::SUBJECT);
    }

    /**
     * Get morph options for ajax requests
     *
     * @param Request $request
     * @param string $morphField 'causer' or 'subject'
     * @return array
     */
    private function getMorphOptions(Request $request, string $morphField): array
    {
        $term = $request->input('term');

        return ActivityLog::select("{$morphField}_type")
            ->distinct()
            ->pluck("{$morphField}_type")
            ->map(function ($type) use ($term) {
                $typeClass = Relation::getMorphedModel($type) ?? $type;

                if (! class_exists($typeClass)) {
                    return;
                }

                $model = new $typeClass();
                return $model
                    ->where($model->identifiableAttribute(), 'like', "%{$term}%")
                    ->limit(5)
                    ->pluck($model->identifiableAttribute(), $model->getKeyName())
                    ->mapWithKeys(fn($value, $id) => ["$type,$id" => Str::limit($value, 28)])
                    ->filter();
            })
            ->flatMap(fn($entry) => $entry)
            ->filter()
            ->toArray();
    }
}
