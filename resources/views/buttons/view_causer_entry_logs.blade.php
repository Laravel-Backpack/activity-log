@php
    $href = backpack_url('activity-log/?'.http_build_query([
        'causer_model' => get_class($crud->model),
        'causer' => join(',', [
            get_class($crud->model),
            $entry->id
        ]),
        'causer_text' => $entry->{$crud->model->identifiableAttribute()} ?? '',
    ]));
@endphp

<a href="{{ $href }}" class="btn btn-sm btn-link">
    <span><i class="las la-stream"></i> {{ ucfirst(__('backpack.activity-log::activity_log.activity_logs')) }}</span>
</a>