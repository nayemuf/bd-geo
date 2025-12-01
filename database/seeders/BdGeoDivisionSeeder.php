<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Nayemuf\BdGeo\Core\Repositories\DivisionRepository;

final class BdGeoDivisionSeeder extends Seeder
{
    public function run(): void
    {
        $table = config('bd-geo.tables.divisions', 'bd_divisions');
        $repo  = new DivisionRepository();

        foreach ($repo->all() as $row) {
            DB::table($table)->updateOrInsert(
                ['id' => (int) $row['id']],
                [
                    'name_en' => $row['name_en'] ?? null,
                    'name_bn' => $row['name_bn'] ?? null,
                    'slug'    => $row['slug'] ?? null,
                ]
            );
        }
    }
}


