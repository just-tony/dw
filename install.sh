#!/usr/bin/env bash
rm -rf ./balance/var/*
rm -rf ./site/var/*
docker-compose run --rm balance composer install \
 && /var/www/bin/console doctrine:database:create  --if-not-exists -n \
 && /var/www/bin/console doctrine:migrations:migrate -n \
 && /var/www/bin/console doctrine:fixtures:load -n

docker-compose run --rm site composer install
