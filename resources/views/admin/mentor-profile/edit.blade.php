{{-- resources/views/admin/mentor-profile/edit.blade.php --}}

@extends('admin.layout')

@section('title', 'Редактировать профиль ментора')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            Редактировать профиль ментора: {{ $mentorProfile->user->name ?? ('ID ' . $mentorProfile->user_id) }}
        </h1>

        <a href="{{ route('admin.mentor-profile.index') }}" class="btn btn-outline-secondary">
            ← Назад к списку
        </a>
    </div>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            @include('admin.mentor-profile._form', [
                'mentorProfile' => $mentorProfile,
                'action' => route('admin.mentor-profile.update', $mentorProfile),
                'method' => 'PUT',
                'submitText' => 'Сохранить изменения'
            ])
        </div>
    </div>

    <div class="card border-danger">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <strong>Опасная зона</strong>
                <div class="text-muted small">
                    Удаление профиля приведёт к потере связанных данных ментора.
                </div>
            </div>

            <form action="{{ route('admin.mentor-profile.destroy', $mentorProfile) }}"
                  method="POST"
                  onsubmit="return confirm('Точно удалить профиль ментора?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger">
                    Удалить профиль
                </button>
            </form>
        </div>
    </div>
@endsection
