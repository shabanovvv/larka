{{-- resources/views/admin/mentor-profile/index.blade.php --}}
@php
    use App\Models\MentorProfile;
@endphp

@extends('admin.layout')

@section('content')
    @php
        $columns = [
            'id' => ['label' => '#', 'sortable' => true],
            'user' => [
                'label' => 'Пользователь',
                'raw' => true,
                'value' => fn(MentorProfile $profile) => $profile->user
                    ? '<a href="' . route('admin.users.edit', $profile->user) . '">' . e($profile->user->name) . '</a>'
                    : '—',
            ],
            'experience_years' => ['label' => 'Опыт (лет)', 'sortable' => true],
            'rate' => ['label' => 'Ставка', 'sortable' => true],
            'is_active' => [
                'label' => 'Активен',
                'format' => 'boolean',
                'sortable' => true,
            ],
            'technologies' => [
                'label' => 'Технологии',
                'value' => fn(MentorProfile $mentorProfile) => $mentorProfile->technologies->pluck('name')->implode(', ')
            ],
            'created_at' => [
                'label' => 'Создан',
                'sortable' => true,
                'format' => 'date',
            ],
        ];
    @endphp

    <x-admin.resource-index
        title="Профили менторов"
        :items="$mentorProfiles"
        entity="mentor-profile"
        create-label="+ Добавить профиль"
        :columns="$columns"
    />
@endsection
