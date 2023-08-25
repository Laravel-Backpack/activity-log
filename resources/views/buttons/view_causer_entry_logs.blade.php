@inject('helper', 'Backpack\ActivityLog\Helpers\ActivityLogHelper')

<a href="{{ $helper->getCauserButtonUrl($entry) }}" class="btn btn-sm btn-link">
    <span><i class="la la-stream"></i> {{ ucfirst(__('backpack.activity-log::activity_log.activity_logs')) }}</span>
</a>