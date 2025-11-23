##################
# Config
##################

APP_ENV ?= test
ENV_FILE = .env.$(APP_ENV)
COMPOSE_FILES = -f docker-compose.yml -f docker/config-envs/$(APP_ENV)/docker-compose.override.yml
DC = docker compose --env-file $(ENV_FILE) $(COMPOSE_FILES)

# Production config (без override)
DC_PROD = docker compose

##################
# Docker Compose - Test/Dev APP_ENV
##################

.PHONY: build up down restart logs ps shell

build:
	$(DC) build

up:
	$(DC) up -d

down:
	$(DC) down -v

restart:
	$(DC) down up

logs:
	$(DC) logs -f

ps:
	$(DC) ps

shell:
	$(DC) exec php bash

##################
# Docker Compose - Production
##################

.PHONY: prod-build prod-up prod-down prod-restart prod-logs

prod-build:
	$(DC_PROD) build

prod-up:
	$(DC_PROD) up -d

prod-down:
	$(DC_PROD) down -v

prod-restart:
	$(DC_PROD) prod-down prod-up

prod-logs:
	$(DC_PROD) logs -f

##################
# Database
##################

.PHONY: db-create db-migrate db-fresh db-seed db-reset

db-create:
	$(DC) exec mysql mysql -uroot -proot \
		-e "CREATE DATABASE IF NOT EXISTS quote_price CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

db-migrate:
	$(DC) exec php php artisan migrate --env=$(APP_ENV)

db-fresh:
	$(DC) exec php php artisan migrate:fresh --env=$(APP_ENV)

db-seed:
	$(DC) exec php php artisan db:seed --env=$(APP_ENV)

db-reset: db-fresh db-seed

##################
# Testing
##################

.PHONY: test test-coverage test-html test-unit test-feature test-filter

test:
	$(DC) exec php vendor/bin/phpunit --colors=always --testdox

test-coverage:
	$(DC) exec php vendor/bin/phpunit --coverage-text --colors=always --testdox

test-html:
	$(DC) exec php vendor/bin/phpunit --coverage-html=storage/coverage-report

test-unit:
	$(DC) exec php vendor/bin/phpunit tests/Unit/ --colors=always --testdox

test-feature:
	$(DC) exec php vendor/bin/phpunit tests/Feature/ --colors=always --testdox

# Usage: make test-filter FILTER=testStoreTask
test-filter:
	$(DC) exec php vendor/bin/phpunit --filter=$(FILTER) --colors=always --testdox

##################
# Artisan Commands
##################

.PHONY: artisan key-generate cache-clear config-clear route-list tinker optimize

artisan:
	$(DC) exec php php artisan $(CMD)

key-generate:
	$(DC) exec php php artisan key:generate

cache-clear:
	$(DC) exec php php artisan cache:clear

config-clear:
	$(DC) exec php php artisan config:clear

route-clear:
	$(DC) exec php php artisan route:clear

clear-all: cache-clear config-clear route-clear

route-list:
	$(DC) exec php php artisan route:list

tinker:
	$(DC) exec php php artisan tinker

optimize:
	$(DC) exec php php artisan optimize

optimize-clear:
	$(DC) exec php php artisan optimize:clear

##################
# Composer
##################

.PHONY: composer-install composer-update composer-dump

composer-install:
	$(DC) exec php composer install

composer-update:
	$(DC) exec php composer update

composer-dump:
	$(DC) exec php composer dump-autoload

##################
# Setup & Init
##################

.PHONY: init setup

# First-time setup for test APP_ENV
init: build up db-create db-fresh
	@echo "Test APP_ENV initialized successfully!"

# Quick setup (assumes containers already exist)
setup: up db-migrate
	@echo "APP_ENV ready!"

##################
# Cleanup
##################

.PHONY: clean docker-prune

clean:
	$(DC) down -v --rmi local --remove-orphans

docker-prune:
	docker system prune -af --volumes
	docker builder prune -af

##################
# Help
##################

.PHONY: help

help:
	@echo "Usage: make [target] [APP_ENV=test|prod]"
	@echo ""
	@echo "Docker (Test/Dev):"
	@echo "  build          Build containers"
	@echo "  up             Start containers"
	@echo "  down           Stop and remove containers"
	@echo "  restart        Restart containers"
	@echo "  logs           Follow container logs"
	@echo "  ps             List containers"
	@echo "  shell          Open bash in PHP container"
	@echo ""
	@echo "Docker (Production):"
	@echo "  prod-build     Build production containers"
	@echo "  prod-up        Start production containers"
	@echo "  prod-down      Stop production containers"
	@echo "  prod-restart   Restart production containers"
	@echo ""
	@echo "Database:"
	@echo "  db-create      Create test database"
	@echo "  db-migrate     Run migrations"
	@echo "  db-fresh       Fresh migrations (drop all tables)"
	@echo "  db-seed        Run seeders"
	@echo "  db-reset       Fresh migrations + seed"
	@echo ""
	@echo "Testing:"
	@echo "  test           Run all tests"
	@echo "  test-coverage  Run tests with coverage report"
	@echo "  test-html      Generate HTML coverage report"
	@echo "  test-unit      Run unit tests only"
	@echo "  test-feature   Run feature tests only"
	@echo "  test-filter    Run specific test (FILTER=testName)"
	@echo ""
	@echo "Artisan:"
	@echo "  artisan        Run artisan command (CMD=...)"
	@echo "  key-generate   Generate app key"
	@echo "  cache-clear    Clear application cache"
	@echo "  config-clear   Clear config cache"
	@echo "  clear-all      Clear all caches"
	@echo "  route-list     List all routes"
	@echo "  tinker         Open Laravel tinker"
	@echo "  optimize       Optimize application"
	@echo ""
	@echo "Composer:"
	@echo "  composer-install  Install dependencies"
	@echo "  composer-update   Update dependencies"
	@echo "  composer-dump     Dump autoload"
	@echo ""
	@echo "Setup:"
	@echo "  init           Full initialization (build + db)"
	@echo "  setup          Quick setup (up + migrate)"
	@echo ""
	@echo "Cleanup:"
	@echo "  clean          Remove containers and images"
	@echo "  docker-prune   Full Docker cleanup"
