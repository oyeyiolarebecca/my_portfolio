# Deployment

This is a standard Laravel app (Laravel 13) with Vite assets.

## Production checklist

- Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://your-domain.com`
- Generate an app key (once): `php artisan key:generate --force`
- Point your web server to the `public/` directory
- Create the storage symlink: `php artisan storage:link`
- Run migrations: `php artisan migrate --force`
- Build frontend assets: `npm ci && npm run build`
- Cache config/routes/views:
  - `php artisan config:cache`
  - `php artisan route:cache`
  - `php artisan view:cache`

If you use a separate frontend, configure CORS and use the API URLs for projects/posts.

## Option A: VPS (Nginx + PHP-FPM) (common)

High level steps:

1) Install PHP 8.3 + extensions, Nginx, and a database (or use SQLite).
2) Deploy the repo to the server.
3) In the project directory:
   - `composer install --no-dev --optimize-autoloader`
   - `npm ci && npm run build`
   - set `.env` (production values)
   - `php artisan migrate --force`
   - `php artisan storage:link`
4) Nginx site root must be `.../public`.

## Option C: Render (API backend) + Vercel (frontend)

This is the recommended split for your setup:

- Vercel hosts your frontend
- Render hosts this Laravel backend (API)

### Important note about uploads (project images)

Render web services have an ephemeral filesystem unless you attach a persistent disk. For truly persistent uploads, store images in a cloud service (S3/Cloudinary/etc.) and save the public URL in `image_url`.

### Render setup (Blueprint)

This repo includes a `render.yaml` Blueprint that creates:

- A Docker web service (`my-portfolio-api`)
- A free Postgres database (`my-portfolio-db`)

Steps:

1) Push this repo to GitHub.
2) In Render: New → Blueprint → select the repo.
3) Set these service env vars in Render (the Blueprint marks them `sync: false`):
   - `APP_URL` = your Render service URL (e.g. `https://...onrender.com`)
   - `APP_KEY` = generate locally with `php artisan key:generate --show` and paste it
   - `CORS_ALLOWED_ORIGINS` = your Vercel domain(s), comma-separated
   - `CONTACT_TO_ADDRESS` / `CONTACT_TO_NAME` = where contact emails should go
4) Deploy.

### Vercel setup

In your Vercel frontend, set the API base URL to your Render backend (e.g. `https://...onrender.com`).
Then load images using `project.image_src` from the API.

## Option B: “No SMTP pain” email (recommended)

For production email delivery, SMTP can be brittle. Consider:

- Resend: set `MAIL_MAILER=resend` + `RESEND_API_KEY`
- Postmark: `composer require symfony/postmark-mailer`, then `MAIL_MAILER=postmark`

See `README.md` for exact env examples.
