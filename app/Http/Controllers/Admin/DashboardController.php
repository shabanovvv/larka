<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Контроллер главной страницы админ-панели.
 * Использует __invoke() для одиночного действия.
 */
class DashboardController extends Controller
{
    /**
     * Отображает админскую панель.
     *
     * @return View
     */
    public function __invoke(): View
    {
        return view('admin.dashboard');
    }
}
