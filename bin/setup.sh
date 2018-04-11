#!/bin/sh

# include shared scripts/helpers
source bin/shared.sh


# customization
random_app_key=`openssl rand -hex 16`

# install composer dependencies
composer install

# generate .environment file from .environment.example
if [ ! -f .environment ]; then
    cp .environment.example .environment
    sedi "s/your_app_key/${random_app_key}/g" .environment
else
    echo "Skipped creation of .environment, delete the file and execute setup.sh again if you want a fresh one."
fi

docker-compose up -d

# custom project setup tasks
if [ ! -f database/database.sqlite ]; then
    composer run create-database
fi

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
