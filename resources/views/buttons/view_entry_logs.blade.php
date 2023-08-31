@inject('helper', 'Backpack\ActivityLog\Helpers\ActivityLogHelper')

<a href="{{ $helper->getButtonUrl($entry, $crud->get('activity-log.options')) }}" class="btn btn-sm btn-link">
    <span><i class="la la-stream"></i> {{ $helper->getButtonTitle($crud->get('activity-log.options')) }}</span>
</a>