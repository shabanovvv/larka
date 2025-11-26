@extends('admin.layout')

@section('title', 'Редактировать технологию')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            Редактировать технологию: {{ $technology->name }}
        </h1>

        <a href="{{ route('admin.technologies.index') }}" class="btn btn-outline-secondary">
            ← Назад к списку
        </a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            @include('admin.technologies._form', [
                'technology' => $technology,
                'action' => route('admin.technologies.update', $technology),
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
                    Удаление технологии может повлиять на связанные сущности.
                </div>
            </div>

            <form action="{{ route('admin.technologies.destroy', $technology) }}"
                  method="POST"
                  onsubmit="return confirm('Точно удалить технологию {{ $technology->name }}?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger">
                    Удалить технологию
                </button>
            </form>
        </div>
    </div>
@endsection
