@props([
    'title',
    'items',
    'createRoute',
    'createLabel' => 'Добавить запись',
    'emptyMessage' => 'Записей пока нет.',
])

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">{{ $title }}</h1>
    <a href="{{ $createRoute }}" class="btn btn-primary">
        {{ $createLabel }}
    </a>
</div>

@if($items->isEmpty())
    <div class="alert alert-info">
        {{ $emptyMessage }}
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            @isset($header)
                <thead>
                <tr>
                    {{ $header }}
                </tr>
                </thead>
            @endisset

            <tbody>
            {{ $slot }}
            </tbody>
        </table>
    </div>

    @if(method_exists($items, 'hasPages') && $items->hasPages())
        {{ $items->links() }}
    @endif
@endif

