{{-- resources/views/admin/users/index.blade.php --}}
@php
    use App\Models\User;
@endphp

@extends('admin.layout')

@section('content')
    @php
        $columns = [
            'id' => ['label' => '#', 'sortable' => true],
            'name' => ['label' => 'Имя', 'sortable' => true],
            'email' => ['label' => 'Email', 'sortable' => true],
            'roles' => [
                'label' => 'Роли',
                'value' => fn(User $user) => $user->roles->pluck('name')->implode(', ')
            ],
            'created_at' => ['label' => 'Дата регистрации', 'sortable' => true, 'format' => 'date'],
        ];
    @endphp
    <x-admin.resource-index
        title="Пользователи"
        :items="$users"
        entity="users"
        create-label="+ Добавить пользователя"
        :columns="$columns"
    >
    </x-admin.resource-index>
@endsection
