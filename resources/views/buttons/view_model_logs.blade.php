@inject('helper', 'Backpack\ActivityLog\Helpers\ActivityLogHelper')

<a href="{{ $helper->getButtonUrl($crud->model, $crud->get('activity-log.options')) }}" bp-button="activity-log-model" class="btn btn-primary">
    <i class="la la-stream"></i><span> {{ $helper->getButtonTitle($crud->get('activity-log.options')) }}</span>
</a>