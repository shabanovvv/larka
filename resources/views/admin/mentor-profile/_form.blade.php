{{-- resources/views/admin/mentor-profile/_form.blade.php --}}

<form action="{{ $action }}" method="POST">
    @csrf
    @if(($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    {{-- Пользователь (пока просто ID, потом можно сделать select) --}}
    <div class="mb-3">
        <label for="user_id" class="form-label">ID пользователя</label>
        <input
            type="number"
            id="user_id"
            name="user_id"
            class="form-control @error('user_id') is-invalid @enderror"
            value="{{ old('user_id', $mentorProfile->user_id ?? '') }}"
            required
        >
        @error('user_id')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Описание --}}
    <div class="mb-3">
        <label for="description" class="form-label">Описание</label>
        <textarea
            id="description"
            name="description"
            class="form-control @error('description') is-invalid @enderror"
            rows="4"
        >{{ old('description', $mentorProfile->description ?? '') }}</textarea>
        @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Опыт (лет) --}}
    <div class="mb-3">
        <label for="experience_years" class="form-label">Опыт (лет)</label>
        <input
            type="number"
            id="experience_years"
            name="experience_years"
            class="form-control @error('experience_years') is-invalid @enderror"
            value="{{ old('experience_years', $mentorProfile->experience_years ?? '') }}"
            min="0"
        >
        @error('experience_years')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Ставка --}}
    <div class="mb-3">
        <label for="rate" class="form-label">Ставка</label>
        <input
            type="number"
            id="rate"
            name="rate"
            class="form-control @error('rate') is-invalid @enderror"
            value="{{ old('rate', $mentorProfile->rate ?? '') }}"
            step="0.01"
            min="0"
        >
        @error('rate')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Активен --}}
    <div class="mb-3 form-check">
        <input
            type="checkbox"
            id="is_active"
            name="is_active"
            class="form-check-input @error('is_active') is-invalid @enderror"
            value="1"
            {{ old('is_active', $mentorProfile->is_active ?? false) ? 'checked' : '' }}
        >
        <label class="form-check-label" for="is_active">Активен</label>
        @error('is_active')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- Кнопки --}}
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('admin.mentor-profile.index') }}" class="btn btn-light">
            Отмена
        </a>

        <button type="submit" class="btn btn-primary">
            {{ $submitText ?? 'Сохранить' }}
        </button>
    </div>
</form>
