<!-- Copilot / AI agent instructions tailored for this Laravel project -->
# Project Snapshot
- **Framework:** Laravel (project skeleton)
- **PHP:** ^8.2 (see `composer.json`)
- **Laravel version:** ^12.0 (dependency in `composer.json`)

# Quick workflows
- **Install & setup (one-liner):**
```
composer run setup
```
This runs `composer install`, copies `.env.example` to `.env` (if missing), generates an app key, runs migrations, installs npm deps and builds assets.
- **Run dev environment:**
```
composer run dev
```
or run frontend/backend separately:
```
php artisan serve
npm run dev
```
- **Run tests:**
```
composer test
# or
php artisan test
```
Note: `phpunit.xml` uses an in-memory SQLite database and sets `QUEUE_CONNECTION=sync`, `CACHE_STORE=array`, and several feature flags to `false` for fast, isolated tests.

# Important files & locations (use these when making edits)
- Routes: `routes/web.php` (HTTP routes). Example: add `Route::get('/x', [App\Http\Controllers\XController::class, 'index']);`.
- Controllers: `app/Http/Controllers/` (controller base is `Controller.php`). Use `php artisan make:controller NameController`.
- Models: `app/Models/` (Eloquent models, e.g. `User.php`). PSR-4 autoloading maps `App\` → `app/`.
- Migrations: `database/migrations/` (schema changes live here).
- Factories: `database/factories/` (model factories used by tests and seeders).
- Seeders: `database/seeders/` (callable from `DatabaseSeeder.php`).
- Views: `resources/views/` (Blade files; `welcome.blade.php` is the example landing view).
- Frontend: `resources/js/`, `resources/css/`, built with Vite. See `package.json` scripts (`dev`, `build`) and `vite.config.js`.
- Tests: `tests/Unit/` and `tests/Feature/` (run via `php artisan test`).

# Project conventions & patterns (discoverable)
- The repo is a Laravel application scaffold — prefer Laravel tooling for tasks: `artisan`, `phpunit` via `php artisan test`, and `composer` scripts.
- Tests expect an isolated environment: `phpunit.xml` sets `DB_CONNECTION=sqlite` and `DB_DATABASE=:memory:` so tests should not require an external DB.
- Background jobs are expected to be synchronous in tests (`QUEUE_CONNECTION=sync`). If you introduce async queueing in local dev, be aware tests will not exercise that behavior unless you override env.
- Frontend is Vite + Tailwind. Assets are compiled via `npm run dev` and `npm run build`.
- Composer `scripts` include a convenience `setup` which attempts to migrate and build assets — do not rely on it for production steps without review.

# How to make common changes (examples)
- Add controller + route:
```
php artisan make:controller ReportController
# then in routes/web.php
Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index']);
```
- Add model + factory: Place model in `app/Models/` and factory under `database/factories/` (see `UserFactory.php`).
- Run a single test file:
```
php artisan test --testsuite=Feature
```

# Integration points & environment notes
- `composer.json` lists `laravel/pail`, `pint`, and `sail` in dev dependencies — these are used for logging/formatting/local containers. Look for `php artisan pail` usage in dev scripts.
- `phpunit.xml` toggles feature flags (`PULSE_ENABLED`, `TELESCOPE_ENABLED`, `NIGHTWATCH_ENABLED`) to `false` — enable cautiously when updating tests that interact with those services.
- The repo expects `artisan` commands for most lifecycle actions (migrate, key:generate, vendor:publish).

# Guidance for AI agents
- Prefer making minimal, focused edits and reference the files above when suggesting changes (routes/controllers/views/tests).
- When proposing code changes, include the exact file path (e.g. `app/Models/Invoice.php`) and a one-line rationale.
- Use the repo's scripts for reproducible commands (prefer `composer test` / `composer run dev`), and note phpunit uses in-memory SQLite for CI consistency.
- Do not remove or assume the presence of `.env.example` — composer scripts copy it if `.env` is missing.

---
If anything here is unclear or you want more detail about a specific area (testing, asset pipeline, or CI expectations), tell me which part and I will expand or adjust these instructions.
