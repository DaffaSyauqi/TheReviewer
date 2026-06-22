# TheReviewer

TheReviewer is a Laravel application for discovering, submitting, and moderating reviewable places on an interactive Indonesia map. Visitors can browse approved places by city or regency, filter them by category, and open place details with uploaded images. Authenticated users can submit places, while administrators can review pending submissions before they appear publicly.

## Features

- Interactive MapLibre-powered Indonesia map with city/regency selection.
- Public review page for approved places, category filtering, and keyword search.
- Authenticated place management for creating and deleting submitted places.
- Admin moderation flow for approving or rejecting pending place submissions.
- Image upload pipeline that converts uploads to WebP and stores metadata on an S3-compatible disk.

## Tech Stack

- Backend: PHP 8.3, Laravel 13
- Frontend: Vue 3, TypeScript, Tailwind CSS 4
- Maps and UI: MapLibre GL, shadcn-vue, Lucide icons, TanStack Vue Table
- Storage: Laravel filesystem with an S3-compatible disk for place images

## Requirements

- PHP 8.3+
- Composer
- Node.js with pnpm 10.x
- MySQL
- S3-compatible object storage for place images, such as Supabase

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

Start the development stack:

```bash
composer run dev
```

This starts the Laravel server, queue listener, and Vite dev server concurrently. Visit `http://localhost:8000` after the processes are ready.

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
