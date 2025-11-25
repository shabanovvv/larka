@extends('admin.layout')

@section('title', 'Технологии')

@section('content')
    <x-admin.resource-index
        title="Технологии"
        :items="$technologies"
        :create-route="route('admin.technologies.create')"
        create-label="+ Добавить технологию"
        empty-message="Пока нет ни одной технологии."
    >
        <x-slot:header>
            <th>#</th>
            <th>Название</th>
            <th>Slug</th>
            <th class="text-end">Действия</th>
        </x-slot:header>

        @foreach($technologies as $tech)
            <tr>
                <td>{{ $tech->id }}</td>
                <td>{{ $tech->name }}</td>
                <td><code>{{ $tech->slug }}</code></td>
                <td class="text-end">
                    <a href="{{ route('admin.technologies.edit', $tech) }}"
                       class="btn btn-sm btn-outline-secondary">
                        Редактировать
                    </a>
                    <form action="{{ route('admin.technologies.destroy', $tech) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Удалить технологию {{ $tech->name }}?')">
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
