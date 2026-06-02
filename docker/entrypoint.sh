#!/bin/sh
set -e

# Ensure Laravel directories are writable
chmod -R 775 /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true

# Generate app key if missing and export it so Laravel sees it without a .env file
if [ -z "$APP_KEY" ]; then
    echo "Generating application key ..."
    APP_KEY=$(cd /var/www && php artisan key:generate --show)
    export APP_KEY
fi

# Ensure SQLite database file exists when using SQLite
DB_FILE="${DB_DATABASE:-/var/www/database/database.sqlite}"
if [ ! -f "$DB_FILE" ]; then
    echo "Creating SQLite database file at $DB_FILE ..."
    mkdir -p "$(dirname "$DB_FILE")"
    touch "$DB_FILE"
    chmod 664 "$DB_FILE"
fi

# Run migrations (and seed if requested)
echo "Running database migrations ..."
cd /var/www && php artisan migrate --force

if [ "${RUN_SEEDER:-false}" = "true" ]; then
    echo "Seeding database ..."
    cd /var/www && php artisan db:seed --force
fi

# Clear caches for safety
echo "Clearing caches ..."
cd /var/www && php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear || true

# Then execute the container's main command (CMD)
exec "$@"
