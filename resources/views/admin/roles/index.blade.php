@extends('admin.layout')

@section('title', 'Роли')

@section('content')
    <x-admin.resource-index
        title="Роли"
        :items="$roles"
        :create-route="route('admin.roles.create')"
        create-label="+ Добавить роль"
        empty-message="Пока нет ни одной роли."
    >
        <x-slot:header>
            <th>#</th>
            <th>Название</th>
            <th class="text-end">Действия</th>
        </x-slot:header>

        @foreach($roles as $role)
            <tr>
                <td>{{ $role->id }}</td>
                <td>{{ $role->name }}</td>
                <td class="text-end">
                    <a href="{{ route('admin.roles.edit', $role) }}"
                       class="btn btn-sm btn-outline-secondary">
                        Редактировать
                    </a>
                    <form action="{{ route('admin.roles.destroy', $role) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Удалить роль {{ $role->name }}?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">
                            Удалить
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </x-admin.resource-index>
@endsection
