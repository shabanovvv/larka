{{-- Общая форма для создания / редактирования пользователя --}}

<form action="{{ $action }}" method="POST">
    @csrf
    @if(($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    {{-- Имя --}}
    <div class="mb-3">
        <label for="name" class="form-label">Имя</label>
        <input
            type="text"
            id="name"
            name="name"
            class="form-control @error('name') is-invalid @enderror"
            placeholder="Иван Петров"
            value="{{ old('name', $user->name ?? '') }}"
            required
        >
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Email --}}
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input
            type="email"
            id="email"
            name="email"
            class="form-control @error('email') is-invalid @enderror"
            placeholder="user@example.com"
            value="{{ old('email', $user->email ?? '') }}"
            required
        >
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Пароль --}}
    <div class="mb-3">
        <label for="password" class="form-label">
            Пароль
            @if($user)
                <span class="text-muted">(оставьте пустым, чтобы не менять)</span>
            @endif
        </label>
        <input
            type="password"
            id="password"
            name="password"
            class="form-control @error('password') is-invalid @enderror"
            placeholder="Минимум 8 символов"
            {{ $user ? '' : 'required' }}
        >
        @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Кнопки --}}
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('admin.users.index') }}" class="btn btn-light">
            Отмена
        </a>

        <button type="submit" class="btn btn-primary">
            {{ $submitText ?? 'Сохранить' }}
        </button>
    </div>
</form>

