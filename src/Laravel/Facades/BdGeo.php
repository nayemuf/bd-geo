<?php

declare(strict_types=1);

namespace Nayemuf\BdGeo\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Nayemuf\BdGeo\Core\GeoManager;

final class BdGeo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return GeoManager::class;
    }
}


