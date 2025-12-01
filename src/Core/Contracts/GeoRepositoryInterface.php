<?php

declare(strict_types=1);

namespace Nayemuf\BdGeo\Core\Contracts;

interface GeoRepositoryInterface
{
    /**
     * @return array<int, array<string,mixed>>
     */
    public function all(): array;

    /**
     * @param int $id
     * @return array<string,mixed>|null
     */
    public function findById(int $id): ?array;
}


