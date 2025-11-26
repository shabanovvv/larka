<aside class="admin-sidebar bg-dark text-white d-flex flex-column p-3">
    <h2 class="fs-4 mb-4">Админка</h2>

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1">
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link text-white @if(request()->routeIs('admin.dashboard')) active @endif">
                Главная
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="{{ route('admin.technologies.index') }}"
               class="nav-link text-white @if(request()->routeIs('admin.technologies.index')) active @endif">
                Технологии
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="{{ route('admin.users.index') }}"
               class="nav-link text-white @if(request()->routeIs('admin.users.index')) active @endif">
                Пользователи
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="{{ route('admin.roles.index') }}"
               class="nav-link text-white @if(request()->routeIs('admin.roles.index')) active @endif">
                Роли
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="{{ route('admin.mentor-profile.index') }}"
               class="nav-link text-white @if(request()->routeIs('admin.mentor-profile.index')) active @endif">
                Профили менторов
            </a>
        </li>
    </ul>

    <hr>

    <div class="small">
        <div>Вы вошли как: <strong>Администратор</strong></div>
        {{-- сюда позже можно добавить ссылку "Выйти" --}}
    </div>
</aside>
