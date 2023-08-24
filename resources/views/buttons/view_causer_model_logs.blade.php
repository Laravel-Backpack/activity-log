@php
    $href = backpack_url('activity-log/?'.http_build_query([
        'causer_model' => get_class($crud->model),
    ]));
@endphp

<a href="{{ $href }}" class="btn btn-primary">
    <span><i class="la la-stream"></i> {{ ucfirst(__('backpack.activity-log::activity_log.activity_logs')) }}</span>
</a>