@extends('admin.layout')

@section('title', 'Редактировать пользователя')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            Редактировать пользователя: {{ $user->name }}
        </h1>

        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            ← Назад к списку
        </a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            @include('admin.users._form', [
                'user' => $user,
                'action' => route('admin.users.update', $user),
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
                    Удаление пользователя приведёт к потере всех связанных данных.
                </div>
            </div>

            <form action="{{ route('admin.users.destroy', $user) }}"
                  method="POST"
                  onsubmit="return confirm('Точно удалить пользователя {{ $user->name }}?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger">
                    Удалить пользователя
                </button>
            </form>
        </div>
    </div>
@endsection








