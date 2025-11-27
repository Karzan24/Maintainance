<!-- Copilot / AI agent instructions tailored for this Laravel project -->
# Project Snapshot
- **Framework:** Laravel 12.x (modern skeleton application)
- **PHP:** ^8.2
- **Backend:** Laravel with Eloquent ORM, PSR-4 autoloading (`App\` → `app/`)
- **Frontend:** Vite 7.x + Tailwind CSS 4.x (compiled to `public/build/`)
- **Database:** SQLite (testing uses in-memory `:memory:`)
- **Task Queue:** Synchronous in tests; can be async locally

## Key Architecture Patterns
- **Unified dev experience:** `composer run dev` runs Vite, Laravel server, queue worker, and logs concurrently via `concurrently` (see `composer.json` dev script)
- **Bootstrap-driven setup:** `bootstrap/app.php` configures routing, middleware, and exception handling; routes and console commands are pulled in from there
- **Testing isolation:** `phpunit.xml` uses in-memory SQLite + synced queues for fast, reproducible tests (not reflective of async production behavior)
- **Minimal scaffolding:** This is a freshly generated Laravel app; controllers, models, and views are created on-demand using artisan generators

# Quick Start Commands
```bash
# One-time setup (copies .env.example → .env, generates keys, migrates, installs frontend deps)
composer run setup

# Development (all services: web server, queue, logs, Vite in one terminal)
composer run dev

# Separate services (useful for debugging)
php artisan serve          # Backend on http://localhost:8000
npm run dev                # Vite dev server with HMR
php artisan queue:listen   # Process queued jobs

# Testing (includes config:clear to avoid caching issues)
composer test              # or: php artisan test

# Asset building
npm run build              # Production build
npm run dev                # Dev with HMR
```

# Essential File Locations & Patterns
| Purpose | Location | Pattern |
|---------|----------|---------|
| Routes (HTTP) | `routes/web.php` | `Route::get('/path', [ControllerClass::class, 'method']);` |
| Controllers | `app/Http/Controllers/` | Namespace: `App\Http\Controllers\NameController` |
| Models & ORM | `app/Models/` | `User.php` extends `Model`; PSR-4 auto-loaded |
| Migrations | `database/migrations/` | Timestamp + name pattern; run with `php artisan migrate` |
| Model Factories | `database/factories/` | E.g., `UserFactory.php` for generating test data |
| Database Seeders | `database/seeders/` | Called from `DatabaseSeeder.php` |
| Views (Blade) | `resources/views/` | `.blade.php` files; e.g., `welcome.blade.php` |
| Frontend CSS | `resources/css/app.css` | Tailwind; bundled by Vite |
| Frontend JS | `resources/js/app.js`, `bootstrap.js` | ES6 modules; bundled by Vite |
| Unit Tests | `tests/Unit/` | Fast, no DB; test pure logic |
| Feature Tests | `tests/Feature/` | Test HTTP routes, DB, queues; use SQLite `:memory:` |

# Development Workflows
## Adding a new route + controller
```bash
php artisan make:controller ReportController
# Then in routes/web.php:
Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index']);
```

## Adding a model + factory + migration
```bash
php artisan make:model Article --migration --factory
# This creates app/Models/Article.php, database/factories/ArticleFactory.php, and a migration
# Edit the migration and factory, then:
php artisan migrate
```

## Running tests
```bash
php artisan test                    # All tests
php artisan test --testsuite=Feature # Feature tests only
php artisan test tests/Feature/ExampleTest.php # Single file
```

# Project Conventions
- **Configuration via environment:** App config is in `config/app.php`, `config/database.php`, etc.; runtime values from `.env`
- **Middleware:** Defined in `bootstrap/app.php` (Line: `->withMiddleware(...)`)
- **Exception handling:** Global exception handler in `bootstrap/app.php` (Line: `->withExceptions(...)`)
- **Service providers:** `AppServiceProvider.php` is the primary place for binding/bootstrapping; extends `ServiceProvider`
- **Asset bundling:** Vite input entry points in `vite.config.js` (`resources/css/app.css` + `resources/js/app.js`); output goes to `public/build/`; Blade calls `@vite([...])` to load bundles
- **Testing database:** Uses in-memory SQLite (`:memory:`) defined in `phpunit.xml` for isolation; migrations run fresh before each test suite
- **Queue & jobs (tests):** `QUEUE_CONNECTION=sync` in `phpunit.xml` ensures queued jobs run immediately, synchronously; production may differ

# Integration Points & Dev Tools
- **Pail:** `laravel/pail` (in `require-dev`) for real-time log streaming; used in `composer run dev` script
- **Pint:** `laravel/pint` for code formatting/linting (PSR-12)
- **Sail:** `laravel/sail` for Docker orchestration (optional; not used in basic setup)
- **Feature flags (tests):** `phpunit.xml` disables `PULSE_ENABLED`, `TELESCOPE_ENABLED`, `NIGHTWATCH_ENABLED` to keep tests fast
- **Hot module reloading:** Vite's HMR detects changes in `resources/` and refreshes the browser automatically during `npm run dev`

# Guidance for AI Agents
1. **Prefer artisan generators** for scaffolding: `php artisan make:controller`, `make:model`, `make:migration` preserve conventions
2. **Minimize edits; reference exact paths** when suggesting changes (e.g., `app/Models/Invoice.php` for a model, `routes/web.php` for a route)
3. **Use repo scripts for reproducible commands:** `composer test`, `composer run dev` encapsulate proper env setup
4. **Remember test isolation:** When adding tests, use factories (`UserFactory::new()`) to seed data; don't assume external DBs or async queues
5. **.env setup:** Never assume `.env` exists; `composer run setup` copies `.env.example` if missing. Don't edit `.env.example` for env-specific secrets
6. **Vite asset paths:** Client code imports from `resources/js/` and `resources/css/`; Blade calls `@vite([...])` to load compiled bundles; check `vite.config.js` for entry points

---
**Questions?** If sections need clarification or expansion (e.g., how to add middleware, database transactions in tests, or custom artisan commands), let me know and I'll refine this further.
