#!/usr/bin/env bash
# migrate.sh – install Composer dependencies and run Laravel migrations
set -e

# Ensure we are in the project root
cd "$(dirname "${BASH_SOURCE[0]}")/.."

echo "Installing Composer dependencies..."
composer install --no-interaction --prefer-dist

echo "Running Laravel migrations..."
php artisan migrate --force

echo "Migration completed successfully."
