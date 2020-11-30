# import config.
# You can change the default config with `make symfonycnf="config_special.env" build`
symfonycnf ?= .env
include $(symfonycnf)
export $(shell sed 's/=.*//' $(symfonycnf))

# HELP
# This will output the help for each task
# thanks to https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
.PHONY: help

help: ## Makefile help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help

up: ## Inits docker containers
	docker-compose up -d

down: ## Destroys docker containers
	docker-compose down

up-and-build: up build ## Inits docker containers and builds project

clear-cache: ## Clears symfony cache for current environment
	docker-compose exec php bin/console cache:clear --env=$(APP_ENV)

composer-install: ## Installs composer dependencies
	docker-compose exec php composer install

drop-database: ## Drops main database
	docker-compose exec php rm -Rf var/data-$(APP_ENV).db

create-database: ## Creates main database
	docker-compose exec php bin/console doctrine:database:create --env=$(APP_ENV)

create-schema: ## Creates main database schema
	docker-compose exec php bin/console doctrine:schema:create --env=$(APP_ENV)

setup-database: drop-database create-database create-schema ## Drops, creates database and creates schema

load-fixtures: setup-database ## Loads fixtures for dev env
	docker-compose exec php bin/console doctrine:fixtures:load --no-interaction --env=$(APP_ENV)

build-without-composer: load-fixtures clear-cache ## Builds project and loads data without installing composer

build: composer-install build-without-composer ## Builds whole project and loads data

phpcsfixer-dry: ## Executes php cs fixer in dry run mode
	docker-compose exec php vendor/bin/php-cs-fixer fix ./src/ --dry-run -vvv

phpcsfixer: ## Executes php cs fixer
	docker-compose exec php vendor/bin/php-cs-fixer fix ./src/

check-security: ## Checks for known security vulnerabilities in dependencies
	docker-compose exec php bin/console security:check

check-code: phpcsfixer-dry check-security ## Executes all check on code

tests-phpunit: ## Executes phpunit tests in test environment
	docker-compose exec php vendor/bin/phpunit

tests-phpspec: ## Executes phpspec tests
	docker-compose exec php vendor/bin/phpspec run -n

tests-all-with-build: build tests-phpunit tests-phpspec ## Rebuild whole project, reload data and run tests


