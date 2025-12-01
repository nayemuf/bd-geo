<?php

declare(strict_types=1);

namespace Nayemuf\BdGeo\Core\Models;

final class Union
{
    public function __construct(
        public readonly int $id,
        public readonly int $upazilaId,
        public readonly string $nameEn,
        public readonly ?string $nameBn = null,
        public readonly ?string $slug = null,
        public readonly ?string $postalCode = null,
        public readonly ?float $latitude = null,
        public readonly ?float $longitude = null,
        public readonly ?string $timezone = null,
        public readonly ?string $geoJson = null,
    ) {
    }
}


