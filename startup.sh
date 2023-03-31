#!/bin/bash

chown -R nobody:www-data /var/www/html/wp-content
docker-entrypoint.sh apache2-foreground
