#!/bin/bash
set -e

# Wait for database to be ready (optional but recommended)
if [ "$DB_CONNECTION" = "mysql" ] || [ "$DB_CONNECTION" = "pgsql" ]; then
    echo "Waiting for database connection..."

    # Define host and port based on connection type
    if [ "$DB_CONNECTION" = "mysql" ]; then
        DB_HOST=${DB_HOST:-mysql}
        DB_PORT=${DB_PORT:-3306}
    else
        DB_HOST=${DB_HOST:-postgres}
        DB_PORT=${DB_PORT:-5432}
    fi

    until nc -z -v -w30 $DB_HOST $DB_PORT; do
        echo "Database ($DB_HOST:$DB_PORT) is unavailable - sleeping"
        sleep 2
    done

    echo "Database is up - continuing"
fi

# Run migrations
echo "Running database migrations..."
php artisan migrate || true

# Cache configuration for better performance
echo "Optimizing application..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Start Laravel Octane with FrankenPHP
exec php artisan octane:frankenphp --host=0.0.0.0 --port=80 "$@"
