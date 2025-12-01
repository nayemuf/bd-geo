<?php

require_once __DIR__ . '/vendor/autoload.php';

use Nayemuf\BdGeo\Core\GeoManager;
use Nayemuf\BdGeo\Core\Repositories\DivisionRepository;
use Nayemuf\BdGeo\Core\Repositories\DistrictRepository;
use Nayemuf\BdGeo\Core\Repositories\UpazilaRepository;
use Nayemuf\BdGeo\Core\Repositories\UnionRepository;

$manager = new GeoManager(
    new DivisionRepository(),
    new DistrictRepository(),
    new UpazilaRepository(),
    new UnionRepository(),
);

echo "Testing BD-Geo Package...\n\n";

$divisions = $manager->divisions();
echo "Divisions count: " . count($divisions) . "\n";
if (count($divisions) > 0) {
    echo "First division: " . $divisions[0]->nameEn . "\n";
}

echo "\n";

$districts = $manager->districts();
echo "Districts count: " . count($districts) . "\n";
if (count($districts) > 0) {
    echo "First district: " . $districts[0]->nameEn . "\n";
}

echo "\n";

$upazilas = $manager->upazilas();
echo "Upazilas count: " . count($upazilas) . "\n";
if (count($upazilas) > 0) {
    echo "First upazila: " . $upazilas[0]->nameEn . "\n";
}

echo "\n";

$unions = $manager->unions();
echo "Unions count: " . count($unions) . "\n";
if (count($unions) > 0) {
    echo "First union: " . $unions[0]->nameEn . "\n";
}

echo "\n";

$totalUpazilaCount = 0;
for ($i=0; $i < count($divisions); $i++) { 
    $upazilaCount = $manager->countUpazilasByDivisionId($divisions[$i]->id);
    echo "Total Upazilas in Division {$divisions[$i]->nameEn} (ID {$divisions[$i]->id}): " . $upazilaCount . "\n";
    $totalUpazilaCount += $upazilaCount;
}

echo "Total Upazilas in all divisions: " . $totalUpazilaCount . "\n\n";

$totalDistrictUpazilaCount = 0;
for ($i=0; $i < count($districts); $i++) { 
    $districtUpazilaCount = $manager->countUpazilasByDistrictId($districts[$i]->id);
    echo "Total Upazilas in District {$districts[$i]->nameEn} (ID {$districts[$i]->id}): " . $districtUpazilaCount . "\n";
    $totalDistrictUpazilaCount += $districtUpazilaCount;
}

echo "Total Upazilas in all districts: " . $totalDistrictUpazilaCount . "\n";

echo "\nDone.\n";
