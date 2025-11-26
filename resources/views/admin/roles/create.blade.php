@extends('admin.layout')

@section('title', 'Добавить роль')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Добавить роль</h1>

        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">
            ← Назад к списку
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @include('admin.roles._form', [
                'role' => null,
                'action' => route('admin.roles.store'),
                'method' => 'POST',
                'submitText' => 'Создать'
            ])
        </div>
    </div>
@endsection
