@inject('helper', 'Backpack\ActivityLog\Helpers\ActivityLogHelper')

<a href="{{ $helper->getButtonUrl($crud->model, $crud->get('activity-log.options')) }}" class="btn btn-primary">
    <span><i class="la la-stream"></i> {{ $helper->getButtonTitle($crud->get('activity-log.options')) }}</span>
</a>