#!/bin/sh

# include shared scripts/helpers
source bin/shared.sh

# customization
random_app_key=`openssl rand -hex 16`

# generate .env file from .env.example
if [ ! -f .env ]; then
    cp .env.example .env
else
    echo "Skipped creation of .env, delete the file and execute setup.sh again if you want a fresh one."
fi

# install composer dependencies
composer install

# generate app key
php artisan key:generate

docker-compose up -d

sleep 8

composer run create-database

# create a new repository?
echo "\n"
echo "Do you want to add a new git origin?"
echo "Provide the full git url of the repository, or leave empty to keep working with the template repository:"

read repository_url

if [ "$repository_url" != "" ]; then
    rm -rf .git
    git init
    git remote add origin $repository_url
    git add .
    git commit -m "Initial project setup"
fi
