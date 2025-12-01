<?php

declare(strict_types=1);

namespace Nayemuf\BdGeo\Tests\Unit;

use Nayemuf\BdGeo\Core\GeoManager;
use Nayemuf\BdGeo\Core\Repositories\DivisionRepository;
use Nayemuf\BdGeo\Core\Repositories\DistrictRepository;
use Nayemuf\BdGeo\Core\Repositories\UpazilaRepository;
use Nayemuf\BdGeo\Core\Repositories\UnionRepository;
use PHPUnit\Framework\TestCase;

final class GeoManagerTest extends TestCase
{
    public function test_it_returns_empty_arrays_with_empty_json(): void
    {
        $manager = new GeoManager(
            new DivisionRepository(),
            new DistrictRepository(),
            new UpazilaRepository(),
            new UnionRepository(),
        );

        self::assertSame([], $manager->divisions());
        self::assertSame([], $manager->districts());
        self::assertSame([], $manager->upazilas());
        self::assertSame([], $manager->unions());
        
        // Test aggregation on empty data
        self::assertSame(0, $manager->countUpazilasByDivisionId(1));
    }
}


