{{-- Общая форма для создания / редактирования технологии --}}

<form action="{{ $action }}" method="POST">
    @csrf
    @if(($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    {{-- Название --}}
    <div class="mb-3">
        <label for="name" class="form-label">Название технологии</label>
        <input
            type="text"
            id="name"
            name="name"
            class="form-control @error('name') is-invalid @enderror"
            placeholder="Например: PHP, Laravel, Vue.js"
            value="{{ old('name', $technology->name ?? '') }}"
            required
        >
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Slug --}}
    <div class="mb-3">
        <label for="slug" class="form-label">Slug</label>
        <input
            type="text"
            id="slug"
            name="slug"
            class="form-control @error('slug') is-invalid @enderror"
            placeholder="php, laravel, vue-js"
            value="{{ old('slug', $technology->slug ?? '') }}"
        >
        <div class="form-text">
            Если оставить пустым — slug будет сгенерирован автоматически.
        </div>
        @error('slug')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Кнопки --}}
    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('admin.technologies.index') }}" class="btn btn-light">
            Отмена
        </a>

        <button type="submit" class="btn btn-primary">
            {{ $submitText ?? 'Сохранить' }}
        </button>
    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('DOMContentLoaded', function () {
                const nameInput = document.getElementById('name');
                const slugInput = document.getElementById('slug');

                if (!nameInput || !slugInput) return;

                let slugManuallyChanged = false;

                const translit = (text) => {
                    const map = {
                        'а':'a','б':'b','в':'v','г':'g','д':'d','е':'e','ё':'e','ж':'zh','з':'z','и':'i',
                        'й':'y','к':'k','л':'l','м':'m','н':'n','о':'o','п':'p','р':'r','с':'s','т':'t',
                        'у':'u','ф':'f','х':'h','ц':'c','ч':'ch','ш':'sh','щ':'shch','ы':'y','э':'e',
                        'ю':'yu','я':'ya','ь':'','ъ':'',
                        // uppercase
                        'А':'a','Б':'b','В':'v','Г':'g','Д':'d','Е':'e','Ё':'e','Ж':'zh','З':'z','И':'i',
                        'Й':'y','К':'k','Л':'l','М':'m','Н':'n','О':'o','П':'p','Р':'r','С':'s','Т':'t',
                        'У':'u','Ф':'f','Х':'h','Ц':'c','Ч':'ch','Ш':'sh','Щ':'shch','Ы':'y','Э':'e',
                        'Ю':'yu','Я':'ya','Ь':'','Ъ':''
                    };

                    return text
                        .split('')
                        .map(ch => map[ch] !== undefined ? map[ch] : ch)
                        .join('');
                };

                slugInput.addEventListener('input', function () {
                    slugManuallyChanged = true;
                });

                nameInput.addEventListener('input', function () {
                    if (slugManuallyChanged) return;

                    const raw = translit(this.value);

                    const slug = raw
                        .toLowerCase()
                        .trim()
                        .replace(/[\s_]+/g, '-')      // пробелы → дефисы
                        .replace(/[^a-z0-9-]+/g, '')  // убрать всё лишнее
                        .replace(/-+/g, '-')          // несколько "-" → один
                        .replace(/^-+|-+$/g, '');     // убрать "-" по краям

                    slugInput.value = slug;
                });
            });
    </script>
@endpush
