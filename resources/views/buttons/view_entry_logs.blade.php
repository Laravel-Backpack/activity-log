@php
    $href = backpack_url('activity-log/?'.http_build_query([
        'subject_model' => get_class($crud->model),
        'subject' => join(',', [
            get_class($crud->model),
            $entry->id
        ]),
        'subject_text' => $entry->{$crud->model->identifiableAttribute()} ?? '',
    ]));
@endphp

<a href="{{ $href }}" class="btn btn-sm btn-link">
    <span><i class="las la-stream"></i> {{ ucfirst(__('backpack.activity-log::activity_log.activity_logs')) }}</span>
</a>