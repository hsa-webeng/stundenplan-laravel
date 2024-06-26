#!/bin/sh
# db-migration-wait.sh

set -e

host="$1"
shift
cmd="php artisan migrate"

until nc -z -v -w30 "$host" 3306
do
  echo "Waiting for database connection..."
  # wait for 2 seconds before check again
  sleep 2
done

>&2 echo "Database is up - migrating..."
exec $cmd
