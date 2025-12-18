<?php

namespace App\Console\Commands;

use App\DTO\PaginateDTO;
use App\DTO\SortDTO;
use App\Repositories\CacheTechnologyRepository;
use App\Repositories\EloquentTechnologyRepository;
use App\Services\TechnologyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TechnologiesCacheBenchmarkCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'technologies:cache:benchmark';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Замеряет производительность paginate() с кэшем и без (и считает SQL-запросы)';

    public function handle(
        TechnologyService $service,
        EloquentTechnologyRepository $repo,
        CacheTechnologyRepository $cacheRepository
    ): int {
        // Значения "зашиты" в коде для простоты запуска команды.
        $iterations = 200;
        $perPage = 20;
        $sort = 'id';
        $direction = 'asc';
        $page = 1;

        $sortDTO = new SortDTO($sort, $direction);
        $paginateDTO = new PaginateDTO($page, $perPage, $sortDTO);

        $phase = null;
        $queryCounts = [
            'no_cache' => 0,
            'cache_cold' => 0,
            'cache_warm' => 0,
        ];

        DB::listen(function () use (&$phase, &$queryCounts) {
            if ($phase !== null && array_key_exists($phase, $queryCounts)) {
                $queryCounts[$phase]++;
            }
        });

        $run = function (string $label, callable $fn) use (&$phase, $iterations): array {
            $phase = $label;
            $start = microtime(true);
            for ($i = 0; $i < $iterations; $i++) {
                $fn();
            }
            $elapsed = microtime(true) - $start;
            $phase = null;

            return [
                'seconds' => $elapsed,
                'avg_ms' => ($elapsed / $iterations) * 1000,
            ];
        };

        // Без кэша: напрямую в репозиторий (обход Cache слоя).
        $noCache = $run('no_cache', fn() => $repo->paginate($paginateDTO));

        // С кэшем: холодный прогон.
        $cacheRepository->clearPaginateCache();
        $cacheCold = $run('cache_cold', fn() => $service->paginate($paginateDTO));

        // С кэшем: тёплый прогон.
        $cacheWarm = $run('cache_warm', fn() => $service->paginate($paginateDTO));

        $speedup = $cacheWarm['seconds'] > 0 ? $noCache['seconds'] / $cacheWarm['seconds'] : null;

        $this->line('--- Benchmark paginate() ---');
        $this->line(sprintf(
            'store(with cache): %s | iterations: %d | page: %d | perPage: %d | sort: %s %s',
            (string) config('cache.default'),
            $iterations,
            $page,
            $perPage,
            $sort,
            $direction
        ));
        $this->line(sprintf('no-cache   : total %.4fs | avg %.2fms | SQL %d', $noCache['seconds'], $noCache['avg_ms'], $queryCounts['no_cache']));
        $this->line(sprintf('cache-cold : total %.4fs | avg %.2fms | SQL %d', $cacheCold['seconds'], $cacheCold['avg_ms'], $queryCounts['cache_cold']));
        $this->line(sprintf('cache-warm : total %.4fs | avg %.2fms | SQL %d', $cacheWarm['seconds'], $cacheWarm['avg_ms'], $queryCounts['cache_warm']));

        if ($speedup !== null) {
            $this->info(sprintf('speedup (no-cache / cache-warm): x%.2f', $speedup));
        }

        return self::SUCCESS;
    }
}


