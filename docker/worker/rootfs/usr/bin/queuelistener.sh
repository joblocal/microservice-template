#!/bin/sh

echo "Starting queue listener"
/usr/bin/php /var/www/artisan queue:listen \
  --tries=4 \
  --sleep=3 \
  --timeout=300 \
  --memory=128
