<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Nayemuf\BdGeo\Core\Repositories\UpazilaRepository;

final class BdGeoUpazilaSeeder extends Seeder
{
    public function run(): void
    {
        $table = config('bd-geo.tables.upazilas', 'bd_upazilas');
        $repo  = new UpazilaRepository();

        foreach ($repo->all() as $row) {
            DB::table($table)->updateOrInsert(
                ['id' => (int) $row['id']],
                [
                    'district_id' => $row['district_id'] ?? null,
                    'name_en'     => $row['name_en'] ?? null,
                    'name_bn'     => $row['name_bn'] ?? null,
                    'slug'        => $row['slug'] ?? null,
                ]
            );
        }
    }
}


