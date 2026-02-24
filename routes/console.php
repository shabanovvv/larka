<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Регулярные задачи (кэширование, сброс, отчёты)
|--------------------------------------------------------------------------
| В cron на каждом сервере достаточно одной записи:
|   * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
| Для кластера: используйте драйвер кэша, общий для всех серверов (redis, database, memcached),
| чтобы onOneServer() работал корректно.
| technologies:cache:benchmark в расписание не включён (ручной запуск для замеров).
*/

// Сброс кэша пагинации технологий раз в сутки (03:00)
Schedule::command('technologies:cache:clear')
    ->dailyAt('03:00')
    ->onOneServer()
    ->name('technologies-cache-clear');

// Прогрев кэша технологий после сброса (03:05)
Schedule::command('technologies:cache:warm')
    ->dailyAt('03:05')
    ->onOneServer()
    ->name('technologies-cache-warm');

// Отчёт по непроверенным работам студентов (ежедневно в 08:00, порог 5 дней)
Schedule::command('codesubmission:forgot', [5])
    ->dailyAt('08:00')
    ->onOneServer()
    ->name('codesubmission-forgot');
