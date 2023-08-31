@inject('helper', 'Backpack\ActivityLog\Helpers\ActivityLogHelper')

<a href="{{ $helper->getButtonUrl($crud->model, $crud->get('activity-log.options')) }}" class="btn btn-primary">
    <span><i class="la la-stream"></i> {{ ucfirst(__('backpack.activity-log::activity_log.activity_logs')) }}</span>
</a>