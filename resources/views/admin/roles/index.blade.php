{{-- resources/views/admin/roles/index.blade.php --}}
@extends('admin.layout')

@section('content')
    @php
        $columns = [
            'id' => ['label' => '#', 'sortable' => true],
            'name' => ['label' => 'Название', 'sortable' => true],
            'users_count' => ['label' => 'Пользователей', 'sortable' => true],
        ];
    @endphp
    <x-admin.resource-index
        title="Роли"
        :items="$roles"
        entity="roles"
        create-label="+ Добавить роль"
        :columns="$columns"
    >
    </x-admin.resource-index>
@endsection
