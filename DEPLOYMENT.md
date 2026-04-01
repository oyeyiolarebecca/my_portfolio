# Deployment (Render backend + Vercel frontend)

## Render (Backend API)

1) Commit and push this repo to GitHub (must include `render.yaml`).
2) In Render: **New** → **Blueprint** → select the repo.
3) Set these env vars on the web service:
   - `APP_URL` = your Render URL (e.g. `https://xxxxx.onrender.com`)
   - `APP_KEY` = run locally: `php artisan key:generate --show`
   - `CORS_ALLOWED_ORIGINS` = your Vercel domain(s), comma-separated
   - `CONTACT_TO_ADDRESS` = where contact messages should go
4) Deploy.

Notes:
- The container runs `php artisan migrate --force` on startup.
- `storage/app/public` is linked to `public/storage` on startup.
- Don’t rely on Render’s local filesystem for long-term image uploads unless you add a persistent disk; prefer S3/Cloudinary.

## Vercel (Frontend)

- Set your API base URL to your Render URL.
- Use `project.image_src` for images so they resolve correctly across domains.

