@extends('admin.layout')

@section('title', 'Редактировать роль')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            Редактировать роль: {{ $role->name }}
        </h1>

        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
            ← Назад к списку
        </a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            @include('admin.roles._form', [
                'role' => $role,
                'action' => route('admin.roles.update', $role),
                'method' => 'PUT',
                'submitText' => 'Сохранить изменения'
            ])
        </div>
    </div>

    {{-- Опционально: кнопка удаления --}}
    <div class="card border-danger">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <strong>Опасная зона</strong>
                <div class="text-muted small">
                    Удаление роли может повлиять на связанные сущности.
                </div>
            </div>

            <form action="{{ route('admin.roles.destroy', $role) }}"
                  method="POST"
                  onsubmit="return confirm('Точно удалить роль {{ $role->name }}?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger">
                    Удалить роль
                </button>
            </form>
        </div>
    </div>
@endsection
