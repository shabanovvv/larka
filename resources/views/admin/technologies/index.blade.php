{{-- resources/views/admin/technologies/index.blade.php --}}
@extends('admin.layout')

@section('content')
    @php
        $columns = [
            'id' => ['label' => '#', 'sortable' => true],
            'name' => ['label' => 'Название', 'sortable' => true],
            'slug' => ['label' => 'Slug', 'sortable' => true, 'format' => 'code'],
        ];
    @endphp
    <x-admin.resource-index
        title="Технологии"
        :items="$technologies"
        entity="technologies"
        create-label="+ Добавить технологию"
        :columns="$columns"
    >
    </x-admin.resource-index>
@endsection
