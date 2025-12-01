# 🇧🇩 BD Geo – Bangladesh Administrative Geodata Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nayemuf/bd-geo.svg?style=flat-square)](https://packagist.org/packages/nayemuf/bd-geo)
[![Total Downloads](https://img.shields.io/packagist/dt/nayemuf/bd-geo.svg?style=flat-square)](https://packagist.org/packages/nayemuf/bd-geo)
[![License](https://img.shields.io/packagist/l/nayemuf/bd-geo.svg?style=flat-square)](https://packagist.org/packages/nayemuf/bd-geo)

**Universal, framework-agnostic PHP package** (Laravel-ready) that ships complete administrative geodata of Bangladesh in a clean, developer-friendly format.

Eliminate the hassle of manually collecting or scraping division, district, and upazila data. **BD Geo** provides a standardized dataset you can use instantly in any PHP application.

---

## 🚀 Features

- **🇧🇩 Complete Hierarchy**: Divisions (8) → Districts (64) → Upazilas (490+) → Unions (4,500+)
- **🔌 Framework Agnostic**: Works with **Laravel**, Symfony, CodeIgniter, or Vanilla PHP.
- **💡 Developer Friendly**:
  - **Laravel Facade**: `BdGeo::divisions()`
  - **Helpers**: Find by ID, get children (e.g., districts of a division), reverse lookup (find parent).
  - **Search**: Simple case-insensitive name search.
  - **Aggregates**: Count upazilas per district/division.
- **📦 Data Included**: Ships with optimized JSON data files (no database required to start).
- **💾 Database Ready**: Includes **Seeders** to import data into your own MySQL/PostgreSQL tables if needed.
- **⚡ Pro-Ready**: Union data structure includes placeholders for **Postal Codes**, **Lat/Long**, and **GeoJSON**.

---

## 📦 Installation

Install via Composer:

```bash
composer require nayemuf/bd-geo
```

### 🔧 Laravel Configuration

The package uses **auto-discovery**. The Service Provider and `BdGeo` facade are registered automatically.

**Optional**: Publish the configuration and seeders.

```bash
# Publish Config (config/bd-geo.php)
php artisan vendor:publish --tag=bd-geo-config

# Publish Seeders (database/seeders/)
php artisan vendor:publish --tag=bd-geo-seeders
```

---

## 📖 Usage

### 1. Retrieving Data (Facade)

Access data instantly without querying a database:

```php
use BdGeo;

// ✅ Get All Records
$divisions = BdGeo::divisions();
$districts = BdGeo::districts();
$upazilas  = BdGeo::upazilas();
$unions    = BdGeo::unions(); // Warning: Large dataset

// ✅ Hierarchical Data (Children)
$districtsInDhaka = BdGeo::districtsByDivisionId(3); // 3 = Dhaka Division ID
$upazilasInDhaka  = BdGeo::upazilasByDistrictId(18); // 18 = Dhaka District ID
$unionsInUpazila  = BdGeo::unionsByUpazilaId(1801);

// ✅ Reverse Lookup (Parents)
$parentDivision = BdGeo::findDivisionByDistrictId(18); // Returns 'Dhaka' Division
$parentDistrict = BdGeo::findDistrictByUpazilaId(1801); // Returns 'Dhaka' District

// ✅ Find Single Record
$dhaka = BdGeo::findDivision(3);
$dhanmondi = BdGeo::findUpazila(1801);

// ✅ Search (Case-insensitive)
$results = BdGeo::searchDistricts('dhaka'); 

// ✅ Aggregation (Counts)
$totalUpazilas = BdGeo::countUpazilasByDivisionId(3);
```

### 2. Using in Vanilla PHP

```php
require 'vendor/autoload.php';

use Nayemuf\BdGeo\Core\GeoManager;
use Nayemuf\BdGeo\Core\Repositories\DivisionRepository;
use Nayemuf\BdGeo\Core\Repositories\DistrictRepository;
use Nayemuf\BdGeo\Core\Repositories\UpazilaRepository;
use Nayemuf\BdGeo\Core\Repositories\UnionRepository;

// Instantiate the Manager
$geo = new GeoManager(
    new DivisionRepository(),
    new DistrictRepository(),
    new UpazilaRepository(),
    new UnionRepository()
);

// Use it
$divisions = $geo->divisions();
print_r($divisions);
```

---

## 💾 Database Seeding (Optional)

By default, the package reads from JSON files. If you need the data in your own database tables (e.g., for foreign keys or complex joins):

1. **Publish Seeders** (if not already done):
   ```bash
   php artisan vendor:publish --tag=bd-geo-seeders
   ```

2. **Create Tables**: Ensure you have tables matching the config (defaults: `bd_divisions`, `bd_districts`, `bd_upazilas`, `bd_unions`). *Migrations are not included to give you full control over your schema.*

3. **Run Seeders**:
   ```bash
   php artisan db:seed --class=BdGeoDivisionSeeder
   php artisan db:seed --class=BdGeoDistrictSeeder
   php artisan db:seed --class=BdGeoUpazilaSeeder
   php artisan db:seed --class=BdGeoUnionSeeder
   ```

---

## 🗺 Data Model

The package uses a normalized ID structure.

- **Division**: `id` (1-8), `name_en`, `name_bn`
- **District**: `id` (1-64), `division_id`, `name_en`, `name_bn`
- **Upazila**: `id`, `district_id`, `name_en`, `name_bn`
- **Union**: `id`, `upazila_id`, `name_en`, `name_bn`, `postal_code`, `lat`, `lng`

---

## 🤝 Contributing

Contributions are welcome! If you notice any incorrect spellings or missing unions, please open a Pull Request updating the JSON files in `resources/data/`.

1. Fork the repository.
2. Create a new branch.
3. Update the JSON files.
4. Submit a PR.

---

## 📄 License

This package is open-sourced software licensed under the [MIT license](LICENSE).

**Built with ❤️ for the Bangladeshi Developer Community.**

