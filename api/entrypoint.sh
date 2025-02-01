#!/bin/bash

set -e

until php bin/console doctrine:query:sql "SELECT 1" >/dev/null 2>&1; do
  sleep 2
done

php bin/console doctrine:database:create --if-not-exists

php bin/console doctrine:migrations:migrate --no-interaction

exec docker-php-entrypoint "$@"
