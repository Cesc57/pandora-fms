DOCKER_COMP = docker-compose
PHP_CONT = $(DOCKER_COMP) exec web
PHP = $(PHP_CONT) php -d memory_limit=-1
COMPOSER = $(PHP_CONT) composer

up: ## Start containers in detached mode and rebuild if necessary
	@$(DOCKER_COMP) up -d --build

down: ## Stop and remove containers
	@$(DOCKER_COMP) down

decode: ## Run decoder.php script to decode the scores
	@$(PHP) /var/www/html/decoder.php
