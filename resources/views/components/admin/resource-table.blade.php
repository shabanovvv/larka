@props([
    'items',
    'columns' => [], // ['key' => 'Название', 'sortable' => true]
    'actions' => true,
    'emptyMessage' => 'Записей пока нет.',
    'entity' => null,
])

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            @foreach($columns as $key => $column)
                @php
                    $label = is_array($column) ? ($column['label'] ?? $key) : $column;
                    $sortable = is_array($column) && (($column['sortable'] ?? false));
                    $currentSort = request('sort') === $key;
                    $currentDirection = request('direction', 'asc');
                @endphp

                <th>
                    @if($sortable)
                        <a href="{{ route("admin.{$entity}.index", [
                                'sort' => $key,
                                'direction' => $currentSort && $currentDirection === 'asc' ? 'desc' : 'asc'
                            ]) }}"
                           class="text-decoration-none text-dark">
                            {{ $label }}
                            @if($currentSort)
                                <i class="fas fa-sort-{{ $currentDirection === 'asc' ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fas fa-sort"></i>
                            @endif
                        </a>
                    @else
                        {{ $label }}
                    @endif
                </th>
            @endforeach

            @if($actions)
                <th class="text-end">Действия</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @forelse($items as $item)
            <tr>
                @foreach($columns as $key => $column)
                    @php
                        $rawValue = is_array($column) ? ($column['value'] ?? null) : null;
                        $format = is_array($column) ? ($column['format'] ?? null) : null;
                        $rawOutput = is_array($column) ? ($column['raw'] ?? false) : false;

                        if (is_callable($rawValue)) {
                            // Если передали callable — вызываем его, формат дальше не трогаем
                            $value = $rawValue($item);
                            $format = null; // формат отключаем, callable сам всё решает
                        } elseif (is_array($column) && array_key_exists('value', $column)) {
                            // Если value задан, но не callable — просто используем его как есть
                            $value = $rawValue;
                        } else {
                            // По умолчанию берём значение из модели по ключу
                            $value = data_get($item, $key);
                        }
                    @endphp

                    <td>
                        @if($format === 'date')
                            {{ $value ? $value->format('d.m.Y H:i') : '-' }}
                        @elseif($format === 'code')
                            <code>{{ $value }}</code>
                        @elseif($format === 'boolean')
                            <span class="badge bg-{{ $value ? 'success' : 'secondary' }}">
                                    {{ $value ? 'Да' : 'Нет' }}
                                </span>
                        @elseif($rawOutput)
                            {!! $value ?? '-' !!}
                        @else
                            {{ $value ?? '-' }}
                        @endif
                    </td>
                @endforeach

                @if($actions)
                    <td class="text-end">
                        <a href="{{ route("admin.{$entity}.edit", $item) }}"
                           class="btn btn-sm btn-outline-secondary">
                            Редактировать
                        </a>
                        <form action="{{ route("admin.{$entity}.destroy", $item) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Удалить роль {{ $item->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                Удалить
                            </button>
                        </form>
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($columns) + ($actions ? 1 : 0) }}" class="text-center py-4">
                    <div class="text-muted">{{ $emptyMessage }}</div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

@if(method_exists($items, 'hasPages') && $items->hasPages())
    <div class="mt-3">
        {{ $items->links() }}
    </div>
@endif
