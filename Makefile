.PHONY: install up down build recreate ps composer-install migrate seed env-copy fresh bash logs test cache-clear restart

install:
	make env-copy
	make build
	make up
	make composer-install
	make migrate
	make seed
	make restart
	make ps

up:
	docker-compose up -d

down:
	docker-compose down

build:
	docker-compose build

recreate:
	docker-compose up -d --build --remove-orphans --force-recreate

ps:
	docker-compose ps

composer-install:
	docker-compose run --rm t4tech-api composer install --no-interaction --prefer-dist --optimize-autoloader

composer-update:
	docker-compose run --rm t4tech-api composer update --no-interaction --prefer-dist

migrate:
	docker-compose run --rm t4tech-api php artisan migrate --force

seed:
	docker-compose run --rm t4tech-api php artisan db:seed --force

fresh:
	docker-compose run --rm t4tech-api php artisan migrate:fresh --seed --force

env-copy:
	@if [ ! -f .env ]; then \
		cp .env.example .env && echo "Copied .env.example to .env"; \
	else \
		echo ".env already exists"; \
	fi

bash:
	docker-compose exec t4tech-api bash

logs:
	docker-compose logs -f t4tech-api

test:
	docker-compose run --rm t4tech-api php artisan test

cache-clear:
	docker-compose run --rm t4tech-api php artisan cache:clear
	docker-compose run --rm t4tech-api php artisan config:clear
	docker-compose run --rm t4tech-api php artisan route:clear

restart:
	docker-compose restart t4tech-api
	make cache-clear
