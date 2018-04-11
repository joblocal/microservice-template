#!/bin/sh

echo "Starting queue listener"
/usr/bin/php /var/www/artisan queue:listen \
  --tries=4 \
  --sleep=0 \
  --timeout=300 \
  --memory=128
