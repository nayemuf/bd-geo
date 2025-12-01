## 🇧🇩 BD Geo – Bangladesh Administrative Geodata Package

Universal, framework-agnostic PHP package (Laravel-ready) that ships complete administrative geodata of Bangladesh in a clean, developer-friendly format.

### Features

- **Full hierarchy**: Divisions, Districts, Upazilas/Thanas, Unions  
- **Multiple formats**: JSON files, PHP arrays, optional Laravel seeders  
- **Query helpers**: get children by parent, lookup by ID, simple fuzzy search by name  
- **Framework-agnostic core**: works with any Composer-based PHP project  
- **First-class Laravel support**: auto-discovery, facade, config, and publishable seeders  
- **Pro-ready fields on unions**: postal code, latitude/longitude, timezone, GeoJSON

## Installation

Install via Composer:

```bash
composer require nayemuf/bd-geo
```

The core library works in any PHP 8.1+ application. In Laravel, the service provider and facade are auto-discovered.

## Data model

The package ships JSON data under `resources/data/`:

- `divisions.json`
- `districts.json`
- `upazilas.json`
- `unions.json`

Each file contains a flat array of records:

- **Division**
  - `id`, `name_en`, `name_bn`, `slug`
- **District**
  - `id`, `division_id`, `name_en`, `name_bn`, `slug`
- **Upazila**
  - `id`, `district_id`, `name_en`, `name_bn`, `slug`
- **Union** (extended / Pro-ready)
  - `id`, `upazila_id`, `name_en`, `name_bn`, `slug`, `postal_code`, `lat`, `lng`, `timezone`, `geojson`

You can replace or extend these JSONs with your own data as long as you keep the same keys.

## Usage in Laravel

After installing the package:

- (Optional) publish config and seeders:

```bash
php artisan vendor:publish --tag=bd-geo-config
php artisan vendor:publish --tag=bd-geo-seeders
```

### Facade API

The package registers a `BdGeo` facade. Common examples:

```php
use BdGeo;

// All entities
$divisions = BdGeo::divisions();
$districts = BdGeo::districts();
$upazilas  = BdGeo::upazilas();
$unions    = BdGeo::unions();

// Hierarchy helpers
$districtsInDhaka   = BdGeo::districtsByDivisionId(3);
$upazilasInDhaka    = BdGeo::upazilasByDistrictId(18);
$unionsInUpazila    = BdGeo::unionsByUpazilaId(1801);

// Single lookups
$dhakaDivision = BdGeo::findDivision(3);
$someDistrict  = BdGeo::findDistrict(18);
$someUpazila   = BdGeo::findUpazila(1801);
$someUnion     = BdGeo::findUnion(10001);

// Simple fuzzy search (case-insensitive contains on English name)
$matchingDistricts = BdGeo::searchDistricts('Dhaka');
```

> Note: The above methods read directly from the JSON data and do **not** require database tables or seeders.

### Optional: Seeding into your database

If you want to persist the data in your own tables (for joins, reporting, etc.):

1. Publish seeders:

   ```bash
   php artisan vendor:publish --tag=bd-geo-seeders
   ```

2. Create migrations for the target tables (defaults in `config/bd-geo.php`):

   - `bd_divisions`
   - `bd_districts`
   - `bd_upazilas`
   - `bd_unions`

3. Run seeders as needed, for example:

   ```bash
   php artisan db:seed --class=BdGeoDivisionSeeder
   php artisan db:seed --class=BdGeoDistrictSeeder
   php artisan db:seed --class=BdGeoUpazilaSeeder
   php artisan db:seed --class=BdGeoUnionSeeder
   ```

The seeders read from the same JSON files and upsert into your tables using the configured names.

## Usage in non-Laravel PHP

You can instantiate `GeoManager` directly and wire the repositories yourself:

```php
use Nayemuf\BdGeo\Core\GeoManager;
use Nayemuf\BdGeo\Core\Repositories\DivisionRepository;
use Nayemuf\BdGeo\Core\Repositories\DistrictRepository;
use Nayemuf\BdGeo\Core\Repositories\UpazilaRepository;
use Nayemuf\BdGeo\Core\Repositories\UnionRepository;

$geo = new GeoManager(
    new DivisionRepository(),
    new DistrictRepository(),
    new UpazilaRepository(),
    new UnionRepository(),
);

$divisions = $geo->divisions();
```

## Versioning & data updates

- **Code** follows semantic versioning (`MAJOR.MINOR.PATCH`).
- **Data changes** (e.g., government renames or boundary changes) are shipped as new releases.  
  - Backward-incompatible ID or structure changes will trigger a **major** version bump.

## License

This package is open-sourced software licensed under the **MIT license**.

For deeper technical details and roadmap, see `docs/srs.md`.

