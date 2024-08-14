@inject('helper', 'Backpack\ActivityLog\Helpers\ActivityLogHelper')

<a href="{{ $helper->getButtonUrl($entry, $crud->get('activity-log.options')) }}" bp-button="activity-log-entry" class="btn btn-sm btn-link">
    <i class="la la-stream"></i> <span>{{ $helper->getButtonTitle($crud->get('activity-log.options')) }}</span>
</a>