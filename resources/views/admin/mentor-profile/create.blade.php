{{-- resources/views/admin/mentor-profile/create.blade.php --}}

@extends('admin.layout')

@section('title', 'Добавить профиль ментора')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Добавить профиль ментора</h1>

        <a href="{{ route('admin.mentor-profile.index') }}" class="btn btn-outline-secondary">
            ← Назад к списку
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @include('admin.mentor-profile._form', [
                'mentorProfile' => null,
                'action' => route('admin.mentor-profile.store'),
                'method' => 'POST',
                'submitText' => 'Создать'
            ])
        </div>
    </div>
@endsection
