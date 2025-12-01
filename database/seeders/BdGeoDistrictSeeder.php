<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Nayemuf\BdGeo\Core\Repositories\DistrictRepository;

final class BdGeoDistrictSeeder extends Seeder
{
    public function run(): void
    {
        $table = config('bd-geo.tables.districts', 'bd_districts');
        $repo  = new DistrictRepository();

        foreach ($repo->all() as $row) {
            DB::table($table)->updateOrInsert(
                ['id' => (int) $row['id']],
                [
                    'division_id' => $row['division_id'] ?? null,
                    'name_en'     => $row['name_en'] ?? null,
                    'name_bn'     => $row['name_bn'] ?? null,
                    'slug'        => $row['slug'] ?? null,
                ]
            );
        }
    }
}


