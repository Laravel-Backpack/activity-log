{{-- custom activity log changes view --}}
@php
$values = $column['value'] ?? data_get($entry, $column['name']);
$old = isset($values['old']);
@endphp

<table class="table table-sm" style="max-width: 480px">
    <tr>
        <th>{{ ucfirst(__('backpack.activity-log::activity_log.key')) }}</th>
        @if($old)
        <th>{{ ucfirst(__('backpack.activity-log::activity_log.previous_value')) }}</th>
        @endif
        <th>{{ ucfirst(__('backpack.activity-log::activity_log.new_value')) }}</th>
    </tr>
    @foreach(($values['attributes'] ?? []) as $key => $new)
    @if(!in_array($key, ['id', 'deleted_at']))
    <tr>
        <td>{{ str_replace('_', ' ', ucfirst(__($key))) }}</td>
        @if($old)
        <td>{{ $values['old'][$key] }}</td>
        @endif
        <td>{{ $new }}</td>
    </tr>
    @endif
    @endforeach
</table>
