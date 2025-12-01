<?php

declare(strict_types=1);

namespace Nayemuf\BdGeo\Core;

use Nayemuf\BdGeo\Core\Models\Division;
use Nayemuf\BdGeo\Core\Models\District;
use Nayemuf\BdGeo\Core\Models\Upazila;
use Nayemuf\BdGeo\Core\Repositories\DivisionRepository;
use Nayemuf\BdGeo\Core\Repositories\DistrictRepository;
use Nayemuf\BdGeo\Core\Repositories\UpazilaRepository;
use Nayemuf\BdGeo\Core\Models\Union;
use Nayemuf\BdGeo\Core\Repositories\UnionRepository;

final class GeoManager
{
    public function __construct(
        private readonly DivisionRepository $divisionRepository,
        private readonly DistrictRepository $districtRepository,
        private readonly UpazilaRepository $upazilaRepository,
        private readonly UnionRepository $unionRepository,
    ) {
    }

    /**
     * @return list<Division>
     */
    public function divisions(): array
    {
        return array_map(
            static fn (array $row): Division => new Division(
                (int) $row['id'],
                (string) $row['name_en'],
                $row['name_bn'] ?? null,
                $row['slug'] ?? null,
            ),
            $this->divisionRepository->all()
        );
    }

    /**
     * @return list<District>
     */
    public function districts(): array
    {
        return array_map(
            static fn (array $row): District => new District(
                (int) $row['id'],
                (int) $row['division_id'],
                (string) $row['name_en'],
                $row['name_bn'] ?? null,
                $row['slug'] ?? null,
            ),
            $this->districtRepository->all()
        );
    }

    /**
     * @return list<Upazila>
     */
    public function upazilas(): array
    {
        return array_map(
            static fn (array $row): Upazila => new Upazila(
                (int) $row['id'],
                (int) $row['district_id'],
                (string) $row['name_en'],
                $row['name_bn'] ?? null,
                $row['slug'] ?? null,
            ),
            $this->upazilaRepository->all()
        );
    }

    /**
     * @return list<Union>
     */
    public function unions(): array
    {
        return array_map(
            static fn (array $row): Union => new Union(
                (int) ($row['id'] ?? 0),
                (int) ($row['upazila_id'] ?? 0),
                (string) ($row['name_en'] ?? ''),
                $row['name_bn'] ?? null,
                $row['slug'] ?? null,
                $row['postal_code'] ?? null,
                isset($row['lat']) ? (float) $row['lat'] : null,
                isset($row['lng']) ? (float) $row['lng'] : null,
                $row['timezone'] ?? null,
                $row['geojson'] ?? null,
            ),
            $this->unionRepository->all()
        );
    }

    /**
     * @return list<District>
     */
    public function districtsByDivisionId(int $divisionId): array
    {
        return array_values(
            array_filter(
                $this->districts(),
                static fn (District $district): bool => $district->divisionId === $divisionId
            )
        );
    }

    /**
     * @return list<Upazila>
     */
    public function upazilasByDistrictId(int $districtId): array
    {
        return array_values(
            array_filter(
                $this->upazilas(),
                static fn (Upazila $upazila): bool => $upazila->districtId === $districtId
            )
        );
    }

    /**
     * @return list<Union>
     */
    public function unionsByUpazilaId(int $upazilaId): array
    {
        return array_values(
            array_filter(
                $this->unions(),
                static fn (Union $union): bool => $union->upazilaId === $upazilaId
            )
        );
    }

    public function findDivision(int $id): ?Division
    {
        $row = $this->divisionRepository->findById($id);

        if ($row === null) {
            return null;
        }

        return new Division(
            (int) $row['id'],
            (string) $row['name_en'],
            $row['name_bn'] ?? null,
            $row['slug'] ?? null,
        );
    }

    public function findDistrict(int $id): ?District
    {
        $row = $this->districtRepository->findById($id);

        if ($row === null) {
            return null;
        }

        return new District(
            (int) $row['id'],
            (int) $row['division_id'],
            (string) $row['name_en'],
            $row['name_bn'] ?? null,
            $row['slug'] ?? null,
        );
    }

    public function findUpazila(int $id): ?Upazila
    {
        $row = $this->upazilaRepository->findById($id);

        if ($row === null) {
            return null;
        }

        return new Upazila(
            (int) $row['id'],
            (int) $row['district_id'],
            (string) $row['name_en'],
            $row['name_bn'] ?? null,
            $row['slug'] ?? null,
        );
    }

    public function findUnion(int $id): ?Union
    {
        $row = $this->unionRepository->findById($id);

        if ($row === null) {
            return null;
        }

        return new Union(
            (int) ($row['id'] ?? 0),
            (int) ($row['upazila_id'] ?? 0),
            (string) ($row['name_en'] ?? ''),
            $row['name_bn'] ?? null,
            $row['slug'] ?? null,
            $row['postal_code'] ?? null,
            isset($row['lat']) ? (float) $row['lat'] : null,
            isset($row['lng']) ? (float) $row['lng'] : null,
            $row['timezone'] ?? null,
            $row['geojson'] ?? null,
        );
    }

    /**
     * Reverse lookup: Get the parent Division of a District.
     */
    public function findDivisionByDistrictId(int $districtId): ?Division
    {
        $district = $this->findDistrict($districtId);

        if ($district === null) {
            return null;
        }

        return $this->findDivision($district->divisionId);
    }

    /**
     * Reverse lookup: Get the parent District of an Upazila.
     */
    public function findDistrictByUpazilaId(int $upazilaId): ?District
    {
        $upazila = $this->findUpazila($upazilaId);

        if ($upazila === null) {
            return null;
        }

        return $this->findDistrict($upazila->districtId);
    }

    /**
     * Reverse lookup: Get the parent Upazila of a Union.
     */
    public function findUpazilaByUnionId(int $unionId): ?Upazila
    {
        $union = $this->findUnion($unionId);

        if ($union === null) {
            return null;
        }

        return $this->findUpazila($union->upazilaId);
    }

    /**
     * Aggregate: Count all upazilas under a specific division.
     */
    public function countUpazilasByDivisionId(int $divisionId): int
    {
        $districts = $this->districtsByDivisionId($divisionId);
        $count = 0;

        foreach ($districts as $district) {
            $count += count($this->upazilasByDistrictId($district->id));
        }

        return $count;
    }
    /**
     * Aggregate: Count all upazilas under a specific district.
     */
    public function countUpazilasByDistrictId(int $districtId): int
    {
        return count($this->upazilasByDistrictId($districtId));
    }

    /**
     * Aggregate: Count all unions under a specific upazila.
     */
    public function countUnionsByUpazilaId(int $upazilaId): int
    {
        return count($this->unionsByUpazilaId($upazilaId));
    }

    /**
     * Simple case-insensitive contains search across entity names (Pro-style helpers).
     *
     * @return list<Division>
     */
    public function searchDivisions(string $query): array
    {
        $q = mb_strtolower($query);

        return array_values(
            array_filter(
                $this->divisions(),
                static fn (Division $division): bool =>
                    str_contains(mb_strtolower($division->nameEn), $q)
            )
        );
    }

    /**
     * @return list<District>
     */
    public function searchDistricts(string $query): array
    {
        $q = mb_strtolower($query);

        return array_values(
            array_filter(
                $this->districts(),
                static fn (District $district): bool =>
                    str_contains(mb_strtolower($district->nameEn), $q)
            )
        );
    }

    /**
     * @return list<Upazila>
     */
    public function searchUpazilas(string $query): array
    {
        $q = mb_strtolower($query);

        return array_values(
            array_filter(
                $this->upazilas(),
                static fn (Upazila $upazila): bool =>
                    str_contains(mb_strtolower($upazila->nameEn), $q)
            )
        );
    }

    /**
     * @return list<Union>
     */
    public function searchUnions(string $query): array
    {
        $q = mb_strtolower($query);

        return array_values(
            array_filter(
                $this->unions(),
                static fn (Union $union): bool =>
                    str_contains(mb_strtolower($union->nameEn), $q)
            )
        );
    }
}


