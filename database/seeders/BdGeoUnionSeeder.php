<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Nayemuf\BdGeo\Core\Repositories\UnionRepository;

final class BdGeoUnionSeeder extends Seeder
{
    public function run(): void
    {
        $table = config('bd-geo.tables.unions', 'bd_unions');
        $repo  = new UnionRepository();

        foreach ($repo->all() as $row) {
            DB::table($table)->updateOrInsert(
                ['id' => (int) $row['id']],
                [
                    'upazila_id'  => $row['upazila_id'] ?? null,
                    'name_en'     => $row['name_en'] ?? null,
                    'name_bn'     => $row['name_bn'] ?? null,
                    'slug'        => $row['slug'] ?? null,
                    'postal_code' => $row['postal_code'] ?? null,
                    'lat'         => $row['lat'] ?? null,
                    'lng'         => $row['lng'] ?? null,
                    'timezone'    => $row['timezone'] ?? null,
                    'geojson'     => $row['geojson'] ?? null,
                ]
            );
        }
    }
}


