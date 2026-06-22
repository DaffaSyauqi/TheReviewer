# TheReviewer

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![Vue](https://img.shields.io/badge/Vue-3.x-42b883?logo=vue.js&logoColor=white)](https://vuejs.org)
[![Inertia](https://img.shields.io/badge/Inertia-3.x-9553E9?logo=inertia&logoColor=white)](https://inertiajs.com)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](#license)

TheReviewer is a Laravel and Vue application for discovering, submitting, and moderating reviewable places on an interactive Indonesia map. Visitors can browse approved places by city or regency, filter them by category, and open place details with uploaded images. Authenticated users can submit places, while administrators can review pending submissions before they appear publicly.

## Features

- Interactive MapLibre-powered Indonesia map with city/regency selection.
- Public review page for approved places, category filtering, and keyword search.
- Authenticated place management for creating and deleting submitted places.
- Admin moderation flow for approving or rejecting pending place submissions.
- Image upload pipeline that converts uploads to WebP and stores metadata on an S3-compatible disk.
- Fortify authentication with registration, email verification, profile settings, password updates, and two-factor authentication.
- Inertia 3, Vue 3, TypeScript, Tailwind CSS 4, and reusable UI components.
- Pest feature tests for authentication, dashboard access, review pages, moderation, and storage deletion behavior.

## Tech Stack

- Backend: PHP 8.3, Laravel 13, Laravel Fortify, Laravel Wayfinder
- Frontend: Vue 3, Inertia 3, TypeScript, Vite 8, Tailwind CSS 4
- Maps and UI: MapLibre GL, Lucide icons, Reka UI, Vaul Vue, TanStack Vue Table
- Storage: Laravel filesystem with an S3-compatible disk for place images
- Testing and quality: Pest 4, PHPUnit 12, Laravel Pint, ESLint, Prettier, Vue TSC

## Requirements

- PHP 8.3+
- Composer
- Node.js with pnpm 10.x
- MySQL or another Laravel-supported database
- S3-compatible object storage for place images, such as AWS S3, MinIO, or a compatible local service

## Getting Started

Clone the repository and install dependencies:

```bash
git clone <repository-url>
cd map-app
composer install
pnpm install
```

Create the environment file and application key:

```bash
cp .env.example .env
php artisan key:generate
```

Configure the main environment values in `.env`:

```dotenv
APP_NAME=TheReviewer
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=the_reviewer
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database
FILESYSTEM_DISK=local

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=ap-southeast-2
AWS_ENDPOINT=
AWS_BUCKET=place-images
AWS_USE_PATH_STYLE_ENDPOINT=true
AWS_URL=
```

Run database migrations:

```bash
php artisan migrate
```

Seed the default development user if needed:

```bash
php artisan db:seed
```

The current seeder creates `test@example.com`. Update `database/seeders/DatabaseSeeder.php` if your local workflow needs different users, roles, or categories.

Start the development stack:

```bash
composer run dev
```

This starts the Laravel server, queue listener, and Vite dev server concurrently. Visit `http://localhost:8000` after the processes are ready.

## Usage

### Browse Places

Open the public review page:

```text
/review
```

Click a city or regency on the map to view approved places in that region. Use the search input and category chips to narrow the list, then open a place to inspect its details and images.

### Submit a Place

Sign in with a verified account and open:

```text
/manage-places/create
```

The place creation form accepts:

- Place name, category, description, full address, city, province, and country.
- Latitude and longitude selected from the location picker.
- Up to 3 images in JPEG, PNG, or WebP format, each up to 5 MB.

New submissions are saved with `pending` status until an administrator moderates them.

### Moderate Places

Administrators can review pending submissions at:

```text
/moderation-places
```

Approving a place marks it as `approved` and makes it visible on `/review`. Rejecting a place marks it as `rejected` and keeps it out of the public review list.

## Useful Commands

```bash
# Run the full local development stack
composer run dev

# Build frontend assets
pnpm run build

# Run the backend test suite
php artisan test --compact

# Run Composer's project test script, including Pint checks
composer test

# Fix PHP formatting
vendor/bin/pint --format agent

# Check frontend linting, formatting, and types
pnpm run lint:check
pnpm run format:check
pnpm run types:check

# Run the combined CI checks configured by the project
composer run ci:check
```

## Project Structure

```text
app/
  Http/Controllers/        Laravel controllers for review, places, moderation, and settings
  Http/Middleware/         Role and Inertia middleware
  Http/Requests/           Form request validation
  Models/                  User, Place, Category, and PlaceImage models
  Services/                Image upload and processing services
database/
  migrations/              Users, categories, places, place images, jobs, and cache tables
  factories/               Test data factories
  seeders/                 Local seed data
resources/js/
  components/              Shared Vue and UI components
  composables/             Frontend data-loading helpers
  layouts/                 Inertia layouts
  pages/                   Inertia pages for public, auth, settings, admin, and place flows
routes/
  web.php                  Public, dashboard, and route includes
  manage-places.php        Authenticated place-management routes
  moderation.php           Admin moderation routes
  settings.php             Account settings routes
tests/
  Feature/                 Laravel feature tests
  Unit/                    Unit tests
```

## Data Model

- `users` own submitted places and may have a `role` for admin-only access.
- `categories` group places and store icon names used by the Vue UI.
- `places` contain location, address, approval status, and moderation metadata.
- `place_images` store disk, path, MIME type, size, and a computed public URL.

Deleting a user deletes their places, deleting a place deletes related image records, and deleting a place image removes the file from its configured storage disk.

## Configuration Notes

- The image upload service currently writes to the `s3` filesystem disk.
- Configure the `AWS_*` values in `.env` before testing uploads outside mocked tests.
- `SESSION_SECURE_COOKIE=true` is present in `.env.example`; use HTTPS locally or set it to `false` for plain HTTP development sessions.
- The test suite uses an in-memory SQLite database as configured in `phpunit.xml`.
- Frontend route helpers are generated through Laravel Wayfinder and Vite.

## Testing

Run the focused backend suite:

```bash
php artisan test --compact
```

Run frontend checks before shipping UI changes:

```bash
pnpm run lint:check
pnpm run format:check
pnpm run types:check
```

For PHP changes, format dirty files before finalizing work:

```bash
vendor/bin/pint --dirty --format agent
```

## Contributing

1. Create a branch for your change.
2. Follow the existing Laravel, Vue, TypeScript, and Tailwind conventions in nearby files.
3. Add or update tests for behavior changes.
4. Run the relevant backend and frontend checks.
5. Open a pull request with a concise description, screenshots for UI changes, and any migration or configuration notes.

Keep changes focused. Prefer small, reviewable pull requests over large unrelated batches.

## Getting Help

- Review the code in `routes/`, `app/Http/Controllers/`, and `resources/js/pages/` for the main request flow.
- Check `tests/Feature/` for executable examples of expected behavior.
- Use the Laravel, Inertia, Vue, and Tailwind documentation for framework-specific guidance.
- Open an issue in this repository with reproduction steps, expected behavior, actual behavior, and relevant logs when reporting bugs.

## Maintainers

This project is maintained by the repository owner and contributors. Ownership and release processes should be documented in repository issues, pull requests, and commit history.

## License

The Composer package metadata declares this project as MIT licensed. Add a `LICENSE` file before distributing the project publicly.
