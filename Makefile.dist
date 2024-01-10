#!/bin/bash

UID = $(shell id -u)
DOCKER_BE = canoe-be
DOCKER_DB = canoe-db

start: ## Start the containers
	docker network create canoe-network || true
	cp -n docker-compose.yml.dist docker-compose.yml || true
	U_ID=${UID} docker-compose up -d

stop: ## Stop the containers
	U_ID=${UID} docker-compose stop

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) start

build: ## Rebuilds all the containers
	docker network create canoe-network || true
	cp -n docker-compose.yml.dist docker-compose.yml || true
	U_ID=${UID} docker-compose build

## Backend commands
composer-install: ## Installs composer dependencies
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} composer install --no-interaction
## End backend commands

ssh-be: ## bash into the be container
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} bash

ssh-be-root: ## bash into the be container with root user
	docker exec -it --user root ${DOCKER_BE} bash

ssh-db-root: ## bash into the db container with root user
	docker exec -it --user root ${DOCKER_DB} bash

code-style: ## Runs php-cs to fix code styling following Symfony rules
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} php-cs-fixer fix . --rules=@Symfony

generate-ssh-keys: ## Generates SSH keys for JWT library
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} mkdir -p config/jwt
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} openssl genrsa -passout pass:5954d598c3e543663afa6c6e85c21f423 -out config/jwt/private.pem -aes256 4096
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} openssl rsa -pubout -passin pass:5954d598c3e543663afa6c6e85c21f423 -in config/jwt/private.pem -out config/jwt/public.pem