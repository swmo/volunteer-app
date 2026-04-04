#!/bin/sh
set -eu

# In local Docker setups, bind mounts and named volumes can create cache/log
# directories as root before PHP-FPM workers start. Symfony needs these paths
# to be writable by the FPM pool user.
mkdir -p /var/www/var/cache /var/www/var/log
chmod -R a+rwX /var/www/var

exec sh ./resources/run_nginx_php.sh
