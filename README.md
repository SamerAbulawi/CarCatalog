# Car Catalog (PHP + MySQL + Vanilla JS)

Arabic RTL car catalog built with PHP fundamentals — no framework, no ORM, no build step.
Browse 10 cars, search by name, filter by type (including favorites-only), sort by name or
price, open a rich details page (specs, KPIs, print-friendly), and save favorites in the browser.

## Features

- Catalog grid loaded from MySQL with stable database ids (`car.php?id=3`)
- Client-side search, type filter, favorites-only toggle, name/price sorting
- Visible result count (`عرض N من M`), Arabic empty state, one-click reset
- Favorites in `localStorage` — catalog hearts + details-page button stay in sync
- Details page: hero, KPI row, full specs incl. torque, EV-aware fuel display, sticky price panel
- Print stylesheet for exporting specs, copy-link button
- Responsive down to ~360px phones; keyboard focus styles; ARIA labels/live regions
- Graceful image fallback (inline SVG placeholder, no external services)
- Clean 404 for unknown/invalid ids, safe 500 for DB failures (no SQL/creds in output)

## Stack

- Plain PHP 7.4+ (tested 8.2), PDO with real prepared statements, `utf8mb4`
- MySQL/MariaDB via XAMPP (`car_catalog` database, see `database/cars.sql`)
- Vanilla JavaScript (`main.js`), plain CSS (`style.css`), no libraries

## Project structure

```
index.php            Catalog (MySQL list + client-side search/filter/sort)
car.php              Details page (single car by id, prepared statement)
config/database.php  DB config (XAMPP defaults) + PDO + error-page helper
includes/layout.php  Shared head/brand/footer + image fallback constant
database/cars.sql    Schema + seed data (import once)
cars.json            Legacy sample only — not used at runtime
main.js              Filters/sort/count/empty-state, favorites, reveal animation
style.css            Dark RTL theme + responsive + print
uploads/             Car images (read-only, referenced by `image` column)
docs/screenshots/    Screenshots (add real captures here)
```

## Requirements

- Windows + [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP 7.4+)
- Project folder inside the web root, e.g. `C:\xampp\htdocs\cars_ultra_pro`

## Setup (Windows + XAMPP)

1. **Start Apache** — XAMPP Control Panel → `Apache` → Start.
2. **Start MySQL** — XAMPP Control Panel → `MySQL` → Start.
3. **Open phpMyAdmin** — http://localhost/phpmyadmin
4. **Create/import the database using `database/cars.sql`:**
   - In phpMyAdmin → `Import` tab → Choose file → select `database\cars.sql` from this project → `Go`.
   - This creates the `car_catalog` database, the `cars` table, and inserts the 10 cars.
   - Optional command-line equivalent (from the project folder):
     ```
     C:\xampp\mysql\bin\mysql.exe -u root < database\cars.sql
     ```
     (In PowerShell, `<` is not supported — use `Get-Content database\cars.sql -Raw | C:\xampp\mysql\bin\mysql.exe -u root`.)
5. **Verify database configuration** — open `config/database.php`. Defaults for local XAMPP:
   `host=localhost`, `database=car_catalog`, `user=root`, `password=(empty)`.
   If your MySQL uses a password/port, edit that file (or set `DB_HOST` / `DB_NAME` / `DB_USER` / `DB_PASS` env vars).
   Quick check in phpMyAdmin: database `car_catalog` → table `cars` → Browse should show **10 rows**.
6. **Open the project in the browser:**
   - http://localhost/cars_ultra_pro/index.php
   - Example details page: http://localhost/cars_ultra_pro/car.php?id=3 (Ford Mustang, Coupe)

### Alternative: PHP built-in server (quick preview, MySQL still required)

From the project folder:

```
C:\xampp\php\php.exe -S localhost:8000
```

Then open http://localhost:8000/index.php (import the database first — step 4 above).

## Screenshots

Captures live in `docs/screenshots/`. Add real screenshots before publishing, e.g.:

- `docs/screenshots/catalog.png` — catalog with search/filter/sort
- `docs/screenshots/details.png` — car details page
- `docs/screenshots/mobile.png` — mobile view (~360px)

```html
<!-- Uncomment once the files exist:
![Catalog](docs/screenshots/catalog.png)
![Details](docs/screenshots/details.png)
![Mobile](docs/screenshots/mobile.png)
-->
```

## Notes

- **MySQL is the primary data source.** The app never reads `cars.json` at runtime; it stays as a legacy/sample reference.
- Links and favorites use stable database ids, so reordering rows never breaks them. Old index-based favorites (0–9) from the JSON era do not carry over.
- Credentials live only in `config/database.php` (server-side) and are never printed into HTML or JavaScript.
