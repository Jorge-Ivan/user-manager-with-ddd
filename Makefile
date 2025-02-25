.PHONY: up down build test migrate

up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose build

bash:
	docker compose exec app bash

test:
	docker compose exec app vendor/bin/phpunit

migrate:
	docker compose exec app php vendor/bin/doctrine orm:schema-tool:update

setup: build up migrate