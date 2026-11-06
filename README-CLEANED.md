# WebRoomSched cleaned project

This package contains the WebRoomSched Laravel application source with a shared, accessible interface for Admin, Faculty, Chair, and Student pages. The role pages share one visual system, consistent header/sidebar styling, clearer typography and controls, responsive layouts, and no global page footer.

## Included cleanup

- Added the shared `public/css/portal-ui.css` interface layer and applied it across the portal.
- Fixed the shared layout sidebar include and added the two missing schedule/request views referenced by active routes.
- Removed only confirmed unused files: old curriculum Blade/model files and `public/css/sidebar.css`.
- Kept migration history and active stylesheet assets intact. Do not delete prior migrations just because a later migration replaces their tables.

## Setup

1. Copy `.env.example` to `.env` and set the database values for your local environment.
2. Install PHP and JavaScript dependencies with `composer install` and `npm ci`.
3. Run `php artisan key:generate`.
4. Create or select your database, then run `php artisan migrate`.
5. Run `php artisan storage:link` and `npm run build`.
6. Start the app with `php artisan serve`.

Do not run `php artisan migrate:fresh` on a database that contains data you need; it deletes existing tables and records.

## Package contents

The ZIP excludes `.env`, Git history, `vendor`, `node_modules`, local SQLite data, logs, compiled caches, and profile-photo uploads. Department logos stored in `storage/app/public/departments` are retained. Install dependencies and configure your own `.env` before running the project.
