DOCKER = docker
DOCKER_COMP = docker-compose
PHP_CONT = $(DOCKER_COMP) exec web
PHP = $(PHP_CONT) php -d memory_limit=-1
MYSQL_CONT = docker exec -it mysql_db
SQL_SCRIPT = ./www/database/create_tables.sql

up: ## Start containers in detached mode and rebuild if necessary
	@$(DOCKER_COMP) up -d --build

down: ## Stop and remove containers
	@$(DOCKER_COMP) down

decoder: ## Run decoder.php script to decode the scores
	@$(PHP) /var/www/html/decoder.php

bash: ## Open a Bash shell inside the PHP container
	@$(PHP_CONT) bash

mysql-bash: ## Open a Bash shell inside the MySQL container
	@$(MYSQL_CONT) bash

mysql-cli: ## Access MySQL CLI inside the MySQL container
	@$(MYSQL_CONT) mysql -u root -p

init-db: ## Initialize the database and create tables
	@$(DOCKER) cp $(SQL_SCRIPT) mysql_db:/create_tables.sql
	@$(MYSQL_CONT) bash -c "mysql -u root -p$$MYSQL_ROOT_PASSWORD < /create_tables.sql"

ps:
	@$(DOCKER) ps
