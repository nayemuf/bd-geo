<?php

declare(strict_types=1);

namespace Nayemuf\BdGeo\Laravel;

use Illuminate\Support\ServiceProvider;
use Nayemuf\BdGeo\Core\GeoManager;
use Nayemuf\BdGeo\Core\Repositories\DivisionRepository;
use Nayemuf\BdGeo\Core\Repositories\DistrictRepository;
use Nayemuf\BdGeo\Core\Repositories\UpazilaRepository;
use Nayemuf\BdGeo\Core\Repositories\UnionRepository;

final class BdGeoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/bd-geo.php', 'bd-geo');

        $this->app->singleton(GeoManager::class, static function (): GeoManager {
            return new GeoManager(
                new DivisionRepository(),
                new DistrictRepository(),
                new UpazilaRepository(),
                new UnionRepository(),
            );
        });

        $this->app->alias(GeoManager::class, 'bd-geo.manager');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/bd-geo.php' => config_path('bd-geo.php'),
        ], 'bd-geo-config');

        $this->publishes([
            __DIR__ . '/../../database/seeders/' => database_path('seeders'),
        ], 'bd-geo-seeders');
    }
}


