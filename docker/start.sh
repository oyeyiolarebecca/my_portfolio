#!/usr/bin/env sh
set -eu

PORT="${PORT:-10000}"

is_truthy() {
  case "${1:-}" in
    1|true|TRUE|yes|YES|on|ON) return 0 ;;
    *) return 1 ;;
  esac
}

if [ -z "${APP_KEY:-}" ]; then
  # Render/Railway often tries to start the service before you've set env vars.
  # Generate an ephemeral key so the app can boot; for stable sessions/encryption,
  # set a persistent APP_KEY in the platform env vars.
  APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
  export APP_KEY
  echo "WARN: APP_KEY was not set; generated an ephemeral APP_KEY for this boot." 1>&2
fi

if [ "${DB_CONNECTION:-}" = "sqlite" ]; then
  if [ -z "${DB_DATABASE:-}" ]; then
    DB_DATABASE="/app/storage/app/database.sqlite"
    export DB_DATABASE
  fi

  DB_DIR="$(dirname "$DB_DATABASE")"
  mkdir -p "$DB_DIR"
  touch "$DB_DATABASE"
fi

php artisan config:clear || true

php artisan package:discover --ansi || true

php artisan storage:link || true

if is_truthy "${MIGRATE_ON_STARTUP:-true}"; then
  php artisan migrate --force
fi

if is_truthy "${SEED_ON_STARTUP:-false}"; then
  php artisan db:seed --force
fi

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec php -S "0.0.0.0:${PORT}" -t public public/index.php
