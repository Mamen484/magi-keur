#!/bin/bash

set -e

echo "✅ Clonage du projet Symfony Demo..."
sudo chmod -R 777 app/*
# git clone https://github.com/symfony/demo.git app

echo "✅ Ajout d'API Platform..."
cd app
composer require api twig/string-extra

echo "✅ Ajout de .env.local avec configuration PostgreSQL..."
echo 'DATABASE_URL="postgresql://magi_user:magi_pass@database:5432/magi?serverVersion=16&charset=utf8"' > .env.local

echo "✅ Installation des dépendances..."
composer install

echo "✅ Création du schéma de base de données..."
php bin/console doctrine:database:create --if-not-exists
php bin/console make:migration --no-interaction
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction

php bin/console make:entity --api-resource
symfony console make:controller NameController

echo "✅ Fini ! Application prête sur https://app.docker.localhost"
