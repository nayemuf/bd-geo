<?php

declare(strict_types=1);

namespace Nayemuf\BdGeo\Core\Models;

final class District
{
    public function __construct(
        public readonly int $id,
        public readonly int $divisionId,
        public readonly string $nameEn,
        public readonly ?string $nameBn = null,
        public readonly ?string $slug = null,
    ) {
    }
}


