-include ./.env
export

.PHONY: help

help: ## Print Help (this message) and exit
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

install: ./.env ## Build the containers
	@docker-compose up -d
	@docker exec php-absolut composer install --no-dev
	@docker exec php-absolut php artisan key:generate
	@docker exec php-absolut php artisan migrate --step
	@docker exec php-absolut php artisan references:sync
	@docker exec php-absolut php artisan optimize
