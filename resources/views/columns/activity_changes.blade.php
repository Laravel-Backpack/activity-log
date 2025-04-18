{{-- custom activity log changes view --}}
@php
$values = $column['value'] ?? data_get($entry, $column['name']);
$old = isset($values['old']);
$deleted = $old && !isset($values['attributes']);
@endphp

<table class="table table-sm table-bordered table-hover">
    <tr>
        <th>{{ ucfirst(__('backpack.activity-log::activity_log.key')) }}</th>
        @if($old)
        <th>{{ ucfirst(__('backpack.activity-log::activity_log.previous_value')) }}</th>
        @endif
        <th>{{ ucfirst(__('backpack.activity-log::activity_log.new_value')) }}</th>
    </tr>
    @if ($deleted)
        @foreach ($values['old'] ?? [] as $key => $new)
            <tr>
                <td class="font-weight-bold">{{ str_replace('_', ' ', ucfirst(__($key))) }}</td>
                @if ($old)
                    <td class="{{ $values['old'][$key] != $new ? 'text-danger' : '' }}">
                        @if (is_array($values['old'][$key]))
                            <ul class="pl-3" style="list-style: circle">
                                @foreach ($values['old'][$key] as $attribute => $value)
                                    <li><strong>{{ $attribute }}</strong>: {{ $value }}</li>
                                @endforeach
                            </ul>
                        @else
                            {{ $values['old'][$key] }}
                        @endif
                    </td>
                @endif
                <td class="{{ isset($values['old']) && $values['old'][$key] != $new ? 'text-success' : '' }}">
                    @if (is_array($new))
                        <ul class="pl-3" style="list-style: circle">
                            @foreach ($new as $attribute => $value)
                                <li><strong>{{ $attribute }}</strong>: {{ $value }}</li>
                            @endforeach
                        </ul>
                    @endif
                </td>
            </tr>
        @endforeach
    @else
        @foreach ($values['attributes'] ?? [] as $key => $new)
            @if (!in_array($key, ['id', 'deleted_at']))
                <tr>
                    <td class="font-weight-bold">{{ str_replace('_', ' ', ucfirst(__($key))) }}</td>
                    @if ($old)
                        <td class="{{ $values['old'][$key] != $new ? 'text-danger' : '' }}">
                            @if (is_array($values['old'][$key]))
                                <ul class="pl-3" style="list-style: circle">
                                    @foreach ($values['old'][$key] as $attribute => $value)
                                        <li><strong>{{ $attribute }}</strong>: {{ $value }}</li>
                                    @endforeach
                                </ul>
                            @else
                                {{ $values['old'][$key] }}
                            @endif
                        </td>
                    @endif
                    <td class="{{ isset($values['old']) && $values['old'][$key] != $new ? 'text-success' : '' }}">
                        @if (is_array($new))
                            <ul class="pl-3" style="list-style: circle">
                                @foreach ($new as $attribute => $value)
                                    <li><strong>{{ $attribute }}</strong>: {{ $value }}</li>
                                @endforeach
                            </ul>
                        @else
                            {{ $new }}
                        @endif
                    </td>
                </tr>
            @endif
        @endforeach
    @endif
</table>
