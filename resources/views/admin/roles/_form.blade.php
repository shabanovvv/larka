{{-- Общая форма для создания / редактирования роли --}}

<form action="{{ $action }}" method="POST">
    @csrf
    @if(($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    {{-- Название --}}
    <div class="mb-3">
        <label for="name" class="form-label">Название роли</label>
        <input
            type="text"
            id="name"
            name="name"
            class="form-control @error('name') is-invalid @enderror"
            placeholder="Admin"
            value="{{ old('name', $role->name ?? '') }}"
            required
        >
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Кнопки --}}
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('admin.roles.index') }}" class="btn btn-light">
            Отмена
        </a>

        <button type="submit" class="btn btn-primary">
            {{ $submitText ?? 'Сохранить' }}
        </button>
    </div>
</form>

@push('scripts')
@endpush
