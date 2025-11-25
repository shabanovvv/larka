@extends('admin.layout')

@section('title', 'Пользователи')

@section('content')
    <x-admin.resource-index
        title="Пользователи"
        :items="$users"
        :create-route="route('admin.users.create')"
        create-label="+ Добавить пользователя"
        empty-message="Пока нет ни одного пользователя."
    >
        <x-slot:header>
            <th>#</th>
            <th>Имя</th>
            <th>Email</th>
            <th>Дата регистрации</th>
            <th class="text-end">Действия</th>
        </x-slot:header>

        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td><code>{{ $user->email }}</code></td>
                <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                <td class="text-end">
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="btn btn-sm btn-outline-secondary">
                        Редактировать
                    </a>
                    <form action="{{ route('admin.users.destroy', $user) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Удалить пользователя {{ $user->name }}?')">
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

