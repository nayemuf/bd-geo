<?php

declare(strict_types=1);

namespace Nayemuf\BdGeo\Core\Repositories;

final class UnionRepository extends AbstractJsonRepository
{
    protected function jsonFileRelativePath(): string
    {
        return 'unions.json';
    }
}


