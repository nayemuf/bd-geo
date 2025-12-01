<?php

declare(strict_types=1);

namespace Nayemuf\BdGeo\Core\Models;

final class Division
{
    public function __construct(
        public readonly int $id,
        public readonly string $nameEn,
        public readonly ?string $nameBn = null,
        public readonly ?string $slug = null,
    ) {
    }
}


