<?php

namespace App\Console\Commands;

use App\Repositories\CacheTechnologyRepository;
use Illuminate\Console\Command;

class TechnologiesCacheClearCommand extends Command
{
    protected $signature = 'technologies:cache:clear';

    protected $description = 'Сбрасывает кэш пагинации списка технологий';

    public function handle(CacheTechnologyRepository $cacheRepository): int
    {
        $cacheRepository->clearPaginateCache();
        $this->info('Кэш пагинации технологий очищен.');

        return self::SUCCESS;
    }
}
