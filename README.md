# Find Diesel Repair (finddieselrepair.com)

Find the closest diesel repair facilities by ZIP code or current location. Results sorted by distance.

## Setup

### 1. Database

Create the tables:

```bash
mysql -u root -p your_db < SQL/fdr-schema.sql
```

### 2. Configuration

Copy `.env.example` to `.env` and set:

- `DB_HOST`, `DB_USER`, `DB_PASSWORD`, `DB_NAME`
- `BASE_URL` (e.g. https://finddieselrepair.com)
- `SITE_NAME` (e.g. Find Diesel Repair)

### 3. Seed Data

Place `ATWRF.csv` in `data/` and run:

```bash
php scripts/seed-db.php
# or: php scripts/seed-db.php /path/to/ATWRF.csv
```

CSV columns (case-insensitive): NAME, ADDRESS, CITY, STATE, ZIPCODE, PHONE, SITE, MAP, RATE, MOBILE, VERIFIED, latitude, longitude, PLACE

### 4. Deploy

Point the web server document root at `public/`.

## Structure

- `public/` — Document root (index.php, search.php)
- `lib/` — Bootstrap, Database, geocode, RepairFacilityRepository
- `SQL/` — Schema (fdr_listings, fdr_zips)
- `scripts/` — seed-db.php

## Get Found

Planned: paid listings feature (like SEO Boost). Tables and API will be added under `public/api/get-found/`.
