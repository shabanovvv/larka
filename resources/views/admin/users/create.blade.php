@extends('admin.layout')

@section('title', 'Добавить пользователя')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Добавить пользователя</h1>

        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            ← Назад к списку
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @include('admin.users._form', [
                'user' => null,
                'action' => route('admin.users.store'),
                'method' => 'POST',
                'submitText' => 'Создать'
            ])
        </div>
    </div>
@endsection








