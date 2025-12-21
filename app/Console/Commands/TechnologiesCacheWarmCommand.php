<?php

namespace App\Console\Commands;

use App\DTO\PaginateDTO;
use App\DTO\SortDTO;
use App\Repositories\CacheTechnologyRepository;
use App\Repositories\EloquentTechnologyRepository;
use App\Services\TechnologyService;
use Illuminate\Console\Command;

class TechnologiesCacheWarmCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'technologies:cache:warm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Прогревает кэш списка технологий (paginate) для выбранных параметров';

    public function handle(TechnologyService $service, CacheTechnologyRepository $cacheRepository): int
    {
        $perPage = 20;
        $pages = 1;
        $sorts = EloquentTechnologyRepository::ALLOWED_SORTS;
        $directions = ['asc', 'desc'];

        // Перед прогревом очищаем кэш пагинации технологий.
        $cacheRepository->clearPaginateCache();

        $count = 0;
        $start = microtime(true);

        foreach ($sorts as $sort) {
            foreach ($directions as $direction) {
                for ($page = 1; $page <= $pages; $page++) {
                    $service->paginate(new PaginateDTO($page, $perPage, new SortDTO($sort, $direction)));
                    $count++;
                }
            }
        }

        $elapsedMs = (microtime(true) - $start) * 1000;
        $this->info(sprintf('Прогрев завершён: %d вызовов paginate(), время %.1f ms', $count, $elapsedMs));

        return self::SUCCESS;
    }
}


