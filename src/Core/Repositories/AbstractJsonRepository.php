<?php

declare(strict_types=1);

namespace Nayemuf\BdGeo\Core\Repositories;

use Nayemuf\BdGeo\Core\Contracts\GeoRepositoryInterface;

abstract class AbstractJsonRepository implements GeoRepositoryInterface
{
    /** @var array<int, array<string, mixed>>|null */
    private ?array $cache = null;

    abstract protected function jsonFileRelativePath(): string;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        $path = $this->resolveDataPath($this->jsonFileRelativePath());

        if (!is_file($path)) {
            $this->cache = [];

            return $this->cache;
        }

        $json = file_get_contents($path);

        if ($json === false) {
            $this->cache = [];

            return $this->cache;
        }

        /** @var array<int, array<string, mixed>> $data */
        $data = json_decode($json, true) ?: [];
        $this->cache = $data;

        return $this->cache;
    }

    public function findById(int $id): ?array
    {
        foreach ($this->all() as $row) {
            if ((int) ($row['id'] ?? 0) === $id) {
                return $row;
            }
        }

        return null;
    }

    private function resolveDataPath(string $relative): string
    {
        // data is expected at resources/data relative to the package root
        return \dirname(__DIR__, 3) . '/resources/data/' . ltrim($relative, '/');
    }
}


