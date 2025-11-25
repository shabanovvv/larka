@extends('admin.layout')

@section('title', 'Панель управления')

@section('content')
    <h1 class="mb-4">Панель управления</h1>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Технологии</h5>
                    <p class="card-text">Справочник технологий, привязка к менторам и заявкам.</p>
                    <a href="{{ route('admin.technologies.index') }}" class="btn btn-primary btn-sm">
                        Открыть
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Пользователи</h5>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">
                        Открыть
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Роли</h5>
                    <p class="card-text">Системные роли: admin, mentor, student.</p>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-primary btn-sm">
                        Открыть
                    </a>
                </div>
            </div>
        </div>

        {{-- потом добавишь карточки для Users, CodeSubmissions и т.п. --}}
    </div>
@endsection
