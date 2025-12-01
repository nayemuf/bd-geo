<?php

declare(strict_types=1);

namespace Nayemuf\BdGeo\Core\Repositories;

final class UpazilaRepository extends AbstractJsonRepository
{
    protected function jsonFileRelativePath(): string
    {
        return 'upazilas.json';
    }
}


