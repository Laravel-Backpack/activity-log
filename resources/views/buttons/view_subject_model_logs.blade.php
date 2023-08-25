@inject('helper', 'Backpack\ActivityLog\Helpers\ActivityLogHelper')

<a href="{{ $helper->getSubjectButtonUrl($crud->model) }}" class="btn btn-primary">
    <span><i class="la la-stream"></i> {{ ucfirst(__('backpack.activity-log::activity_log.activity_logs')) }}</span>
</a>