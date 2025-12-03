@props([
    'title',
    'items',
    'columns' => [],
    'entity' => null,
    'createLabel' => 'Добавить запись',
    'emptyMessage' => 'Записей пока нет.',
])

@section('title', $title)

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">{{ $title }}</h1>
    <a href="{{ route("admin.{$entity}.create") }}" class="btn btn-primary">
        {{ $createLabel }}
    </a>
</div>

<x-admin.resource-table
    :items="$items"
    :columns="$columns"
    :empty-message="$emptyMessage"
    :entity="$entity"
>
</x-admin.resource-table>







