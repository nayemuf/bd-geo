## Software Requirements Specification (SRS)

**Project**: 🇧🇩 Bangladesh Administrative Geodata Package (`bd-geo`)  
**Type**: PHP library (Laravel-ready, framework-agnostic)  
**Version**: 0.1.0 (Draft)  
**Author**: _TBD_

---

## 1. Introduction

### 1.1 Purpose

The purpose of the `bd-geo` package is to provide a standardized, reusable, and officially aligned source of administrative geodata for Bangladesh for any PHP application.  
It aims to eliminate repetitive manual entry of geographic data, ensure consistency across systems, and offer a clean API to query and integrate this data in web, mobile, and enterprise applications.

### 1.2 Scope

The package will:

- Provide complete **Bangladesh administrative hierarchy**:
  - Divisions
  - Districts
  - Upazilas / Thanas
- Support multiple **distribution formats**:
  - JSON
  - PHP arrays
  - Laravel seeders
  - SQL dump (optional export)
- Offer **search and filter APIs**:
  - Get all districts in a division
  - Get all thanas in a district
  - Lookup by ID/name
- Be implemented as a **Composer package**, compatible with:
  - Laravel (with auto-discovery)
  - Symfony
  - CodeIgniter
  - Any vanilla PHP application

Future **Pro** (premium) features will extend the same package family with:

- Union-level data
- Postal codes
- Geospatial (lat/long, GeoJSON, boundaries)
- Cached & API-enabled access

### 1.3 Definitions, Acronyms, and Abbreviations

- **Division**: Top-level administrative unit in Bangladesh.
- **District**: Sub-unit of a Division.
- **Upazila/Thana**: Sub-unit of a District.
- **PSR-4**: PHP Standards Recommendation for autoloading classes.
- **Seeder**: Laravel mechanism for populating databases with initial data.
- **GeoJSON**: Geospatial data interchange format (for future Pro).

### 1.4 References

- Bangladesh Government administrative data (LGED, BBS, etc.) – for verification.
- PHP-FIG PSR-1, PSR-4.
- Laravel Package Development documentation.

---

## 2. Overall Description

### 2.1 Product Perspective

`bd-geo` is a standalone PHP library distributed via Composer/Packagist. It exposes:

- A **core data layer** (JSON + PHP arrays).
- A **query API** (PHP classes) for programmatic access.
- **Framework integrations** (Laravel service provider, facade, seeders).

It will not maintain its own runtime database by default. Instead, it provides:

- In-memory data access (arrays, collections).
- Optional seeder/migration support to populate the host application's database.

### 2.2 Product Functions (High-Level)

- **F1**: Provide raw administrative geodata in multiple formats.
- **F2**: Expose PHP API to retrieve:
  - All divisions, districts, upazilas.
  - Children of a parent (e.g., districts by division ID).
  - Single record by ID or name.
- **F3**: Provide Laravel seeders for importing data into DB tables.
- **F4**: Offer optional configuration (e.g., table names, column names).
- **F5** (future/pro): Provide API endpoints and UI components.

### 2.3 User Classes and Characteristics

- **PHP / Laravel developers**:
  - Use data in forms, dependent dropdowns, filters, reports.
- **Enterprise application developers**:
  - Need consistent geodata across ERP, logistics, banking, etc.
- **Mobile/backend developers**:
  - Need an API or seeders for their backend services.

### 2.4 Operating Environment

- PHP >= 8.1
- Any OS supported by PHP.
- For Laravel features: Laravel >= 9.x (configurable).

### 2.5 Design and Implementation Constraints

- Must follow **PSR-4** autoloading and coding standards.
- Must be **framework-agnostic** at core.
- All public APIs should be **backward compatible** across minor versions as much as possible.
- No external DB or caching dependency by default.

### 2.6 Assumptions and Dependencies

- Developers will integrate the package via Composer.
- For Laravel, auto-discovery will be used by default (with an option to register manually).
- Official data sources are reasonably stable; changes will be versioned (e.g., government renames districts).

---

## 3. Specific Requirements

### 3.1 Functional Requirements

#### FR-1: Core Data Access

- **FR-1.1**: The package SHALL provide a method to retrieve all divisions.
- **FR-1.2**: The package SHALL provide a method to retrieve all districts.
- **FR-1.3**: The package SHALL provide a method to retrieve all upazilas/thanas.
- **FR-1.4**: The package SHALL expose the raw data in PHP array format.

#### FR-2: Hierarchical Queries

- **FR-2.1**: Given a division ID, the system SHALL return all its districts.
- **FR-2.2**: Given a district ID, the system SHALL return all its upazilas/thanas.
- **FR-2.3**: Given a division ID, the system SHOULD be able to return all upazilas/thanas under that division (via districts).

#### FR-3: Lookup by ID/Name

- **FR-3.1**: Given an entity ID (division/district/upazila), the system SHALL return its full record.
- **FR-3.2**: Given an entity name (partial or full, case-insensitive), the system SHOULD return matching records.
- **FR-3.3**: The system SHOULD provide helper methods like:
  - `getDivisionByName(string $name)`
  - `getDistrictsByDivisionName(string $name)`

#### FR-4: Data Formats

- **FR-4.1**: The package SHALL ship JSON files for divisions, districts, and upazilas/thanas.
- **FR-4.2**: The package SHALL provide PHP array representations using the JSON source.
- **FR-4.3**: The package SHOULD provide an SQL dump or export command (optional).

#### FR-5: Laravel Integration

- **FR-5.1**: The package SHALL include a Laravel service provider.
- **FR-5.2**: The package SHALL register a facade `BdGeo` for easy access to APIs.
- **FR-5.3**: The package SHALL include Laravel seeders to populate:
  - `bd_divisions`
  - `bd_districts`
  - `bd_upazilas`
- **FR-5.4**: The package SHALL allow overriding default table names via config file `config/bd-geo.php`.

#### FR-6: Configuration

- **FR-6.1**: The package SHALL provide a configuration file allowing:
  - Custom table names.
  - Whether to publish data as seeders only or migrations + seeders.
- **FR-6.2**: Configuration SHALL be publishable in Laravel via `php artisan vendor:publish`.

#### FR-7: Pro Version (Future Scope)

*(Documented for roadmap; not in initial free version)*

- **FR-7.1**: Support unions and postal codes.
- **FR-7.2**: Include latitude/longitude per entity.
- **FR-7.3**: Provide GeoJSON boundaries and geospatial queries.
- **FR-7.4**: Add artisan commands to sync with remote API or updated data source.

---

### 3.2 Non-Functional Requirements

#### NFR-1: Performance

- **NFR-1.1**: Query operations on in-memory data SHOULD execute in under 50ms for common use cases on a typical server.
- **NFR-1.2**: Loading all data into memory SHOULD be done lazily (on first use) or via lightweight initialization.

#### NFR-2: Reliability & Data Integrity

- **NFR-2.1**: Data MUST be validated against official government/authoritative sources.
- **NFR-2.2**: Any breaking change to IDs or structure MUST result in a major version bump.

#### NFR-3: Maintainability

- **NFR-3.1**: The codebase MUST follow PSR-1/PSR-12 coding standards.
- **NFR-3.2**: Classes MUST be autoloaded using PSR-4.
- **NFR-3.3**: Public APIs SHOULD be documented with PHPDoc.

#### NFR-4: Portability

- **NFR-4.1**: The package MUST not depend on a specific framework in its core.
- **NFR-4.2**: All framework-specific features MUST live in dedicated namespaces (e.g., `BdGeo\\Laravel`).

---

## 4. System Design Overview

### 4.1 High-Level Architecture

- **Core Layer** (`BdGeo\Core`):
  - Holds the domain models and repositories to load/query data from JSON.
- **Data Layer** (`resources/data`):
  - JSON files containing normalized administrative data.
- **Integration Layer**:
  - `BdGeo\Laravel\BdGeoServiceProvider`
  - `BdGeo\Laravel\Facades\BdGeo`
  - Seeders under `database/seeders`.

### 4.2 Main Components

- **GeoManager** (core service):
  - Singleton-style service for reading and querying geodata.
- **Repositories**:
  - `DivisionRepository`
  - `DistrictRepository`
  - `UpazilaRepository`
- **Models/DTOs**:
  - `Division`
  - `District`
  - `Upazila`

---

## 5. External Interface Requirements

### 5.1 PHP API

Planned public methods (subject to refinement during implementation):

- `BdGeo::divisions(): Collection|array`
- `BdGeo::districts(): Collection|array`
- `BdGeo::upazilas(): Collection|array`
- `BdGeo::districtsByDivisionId(int|string $divisionId)`
- `BdGeo::upazilasByDistrictId(int|string $districtId)`
- `BdGeo::findDivision(int|string $id)`
- `BdGeo::findDistrict(int|string $id)`
- `BdGeo::findUpazila(int|string $id)`

*(The exact signatures may use typed collections or custom return types.)*

### 5.2 Laravel Integration

- Auto-discovery via `composer.json` `extra.laravel.providers`.
- Facade alias `BdGeo`.
- Config file `config/bd-geo.php`.
- Publishable resources:
  - `php artisan vendor:publish --tag=bd-geo-config`
  - `php artisan vendor:publish --tag=bd-geo-seeders`

---

## 6. Data Requirements

### 6.1 Data Model

- **Division**
  - `id` (int, stable)
  - `name_en` (string)
  - `name_bn` (string, optional)
  - `slug` (string, optional)

- **District**
  - `id` (int, stable)
  - `division_id` (int, FK -> Division)
  - `name_en`
  - `name_bn` (optional)
  - `slug` (optional)

- **Upazila/Thana**
  - `id` (int, stable)
  - `district_id` (int, FK -> District)
  - `name_en`
  - `name_bn` (optional)
  - `slug` (optional)

Future Pro data fields (not in free MVP):

- Postal code
- Latitude/Longitude
- GeoJSON geometry

---

## 7. Project Structure (Planned)

### 7.1 Directory Layout

```text
bd-geo/
├─ src/
│  ├─ Core/
│  │  ├─ GeoManager.php
│  │  ├─ Contracts/
│  │  │  ├─ GeoRepositoryInterface.php
│  │  ├─ Models/
│  │  │  ├─ Division.php
│  │  │  ├─ District.php
│  │  │  ├─ Upazila.php
│  │  ├─ Repositories/
│  │  │  ├─ DivisionRepository.php
│  │  │  ├─ DistrictRepository.php
│  │  │  ├─ UpazilaRepository.php
│  ├─ Laravel/
│  │  ├─ BdGeoServiceProvider.php
│  │  ├─ Facades/
│  │  │  ├─ BdGeo.php
│  ├─ Support/
│     ├─ Helpers.php
│
├─ resources/
│  ├─ data/
│  │  ├─ divisions.json
│  │  ├─ districts.json
│  │  ├─ upazilas.json
│
├─ config/
│  ├─ bd-geo.php
│
├─ database/
│  ├─ seeders/
│  │  ├─ BdGeoDivisionSeeder.php
│  │  ├─ BdGeoDistrictSeeder.php
│  │  ├─ BdGeoUpazilaSeeder.php
│
├─ tests/
│  ├─ Unit/
│  ├─ Feature/
│
├─ docs/
│  ├─ srs.md
│
├─ composer.json
├─ README.md
```

---

## 8. Future Enhancements & Pro Version

- Union and postal code data.
- GeoJSON boundary support and map integration.
- API controllers and routes for ready-to-use endpoints.
- Caching layer (Redis/array cache) for large-scale apps.
- Admin tools to diff and update data when the government changes boundaries.

---

## 9. Approval

This SRS is a **living document**. Once you approve the requirements and project structure, we will proceed with:

1. Creating the actual folder and class skeletons as per PSR-4.
2. Implementing the core `GeoManager` and repositories.
3. Adding JSON data and validation.
4. Wiring Laravel service provider, config, and seeders.


