PROJECT_NAME=magi-keur
APP_SERVICE=php

SHELL := /bin/bash

.DEFAULT_GOAL := help

.PHONY: help init build up down restart clean cache logs shell composer symfony install require database migrations fixtures factory entity api-resource controller

help:
	@echo "Available commands:"
	@echo "  make init       -> Build, start containers & install dependencies"
	@echo "  make build      -> Build Docker images"
	@echo "  make up         -> Start all containers"
	@echo "  make down       -> Stop all containers"
	@echo "  make logs       -> Tail all logs"
	@echo "  make shell      -> Access Symfony container shell"
	@echo "  make composer   -> Run Composer commands inside container"
	@echo "  make symfony    -> Run Symfony CLI inside container"
	@echo "  make entity     -> Generate entity"
	@echo "  make controller -> Generate controller"
	@echo "  make require    -> Install PHP & JS dependencies"
	@echo "  make database   -> Create database if not exists"
	@echo "  make migrations -> Create and execute migrations"
	@echo "  make fixtures   -> Load data fixtures"
	@echo "  make factory    -> Create a persistent object factory for one of your entities"
	@echo "  make api-resource   -> Generate entity for api-platform"
	@echo "  make postgres   -> Connexion à la base depuis un terminal"

init: build up install

build:
	docker-compose build

up: 
	docker-compose up -d

down:
	docker-compose down

restart:
	@echo "🔄 Redémarrage des services..."
	docker-compose down --remove-orphans
	docker-compose up -d

clean:
	@echo "🧹 Nettoyage complet (conteneurs, volumes, images)..."
	docker-compose down -v --rmi all

cache:
	@echo "🧹 Nettoyage complet (cache Symfony)..."
	docker-compose exec $(APP_SERVICE) php bin/console cache:clear

logs:
	docker-compose logs -f

shell:
	docker-compose exec $(APP_SERVICE) bash

composer:
	docker-compose exec $(APP_SERVICE) composer

symfony:
	docker-compose exec $(APP_SERVICE) php bin/console

entity:
	docker-compose exec $(APP_SERVICE) php bin/console make:entity

controller:
	docker-compose exec $(APP_SERVICE) php bin/console make:controller

install:
	docker-compose exec $(APP_SERVICE) composer install

require:
	@echo "📦 Installing PHP & JS dependencies..."
	docker-compose exec $(APP_SERVICE) composer require api twig/string-extra dompdf/dompdf easycorp/easyadmin-bundle symfony/ux-turbo symfony/stimulus-bundle symfony/mailer stripe/stripe-php symfony/filesystem league/flysystem-bundle symfony/messenger symfony/doctrine-messenger symfony/mercure-bundle jsor/doctrine-postgis
	docker-compose exec $(APP_SERVICE) composer require --dev zenstruck/foundry

drop:
	@echo "✅ Deleting and Creating database if exists..."
	docker-compose exec $(APP_SERVICE) php bin/console doctrine:database:drop --force --if-exists
	docker-compose exec $(APP_SERVICE) php bin/console doctrine:migrations:version --delete --all -n
# 	docker-compose exec $(APP_SERVICE) php bin/console doctrine:database:create
# 	docker-compose exec $(APP_SERVICE) php bin/console doctrine:schema:update --force

database:
	@echo "✅ Creating database if not exists..."
	docker-compose exec $(APP_SERVICE) php bin/console doctrine:database:create --if-not-exists

migrations:
	@echo "🛠 Generating migration... --no-interaction"
	docker-compose exec $(APP_SERVICE) php bin/console make:migration --no-interaction
	@echo "🚀 Applying migrations..."
	docker-compose exec $(APP_SERVICE) php bin/console doctrine:migrations:migrate --no-interaction

fixtures:
	docker-compose exec $(APP_SERVICE) php bin/console doctrine:fixtures:load

factory:
	docker-compose exec $(APP_SERVICE) php bin/console make:factory

validate:
	docker-compose exec $(APP_SERVICE) php bin/console doctrine:schema:validate
	docker-compose exec $(APP_SERVICE) php bin/console doctrine:schema:update --force

api-resource:
	docker-compose exec $(APP_SERVICE) php bin/console make:entity --api-resource

postgres:
# 	docker exec -it database psql -U magi_user -d postgres
	docker exec -it database psql -U magi_user -d magi
# 	CREATE EXTENSION IF NOT EXISTS postgis;

# End of Makefile
