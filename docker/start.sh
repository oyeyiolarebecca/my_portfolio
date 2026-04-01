#!/usr/bin/env sh
set -eu

PORT="${PORT:-10000}"

if [ -z "${APP_KEY:-}" ]; then
  echo "ERROR: APP_KEY is not set. Set it in Render env vars." 1>&2
  exit 1
fi

php artisan config:clear || true

php artisan package:discover --ansi || true

php artisan storage:link || true

php artisan migrate --force || true

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec php -S "0.0.0.0:${PORT}" -t public public/index.php
