#!/bin/sh
set -e

# Setup directories and permissions
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chmod -R 777 storage bootstrap/cache

# Setup .env if missing
if [ ! -f ".env" ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
    php artisan key:generate --force
fi

# Ensure SQLite file exists if using sqlite
if [ ! -f "database/database.sqlite" ]; then
    echo "Creating database/database.sqlite..."
    touch database/database.sqlite
fi
chmod 777 database database/database.sqlite 2>/dev/null || true

# Run database migrations
echo "Running migrations..."
php artisan migrate --force

# Remove any stale public/hot so CSS/JS load correctly from public/build
rm -f public/hot

# Ensure production frontend assets exist
if [ ! -f "public/build/manifest.json" ]; then
    echo "Building frontend assets..."
    npm run build
fi

echo "PhilNITS Prep is ready!"
exec "$@"
