@extends('admin.layout')

@section('title', 'Добавить технологию')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Добавить технологию</h1>

        <a href="{{ route('admin.technologies.index') }}" class="btn btn-outline-secondary">
            ← Назад к списку
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @include('admin.technologies._form', [
                'technology' => null,
                'action' => route('admin.technologies.store'),
                'method' => 'POST',
                'submitText' => 'Создать'
            ])
        </div>
    </div>
@endsection
