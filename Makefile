$(shell cp -n .env.example .env)
include .env
.DEFAULT_GOAL := help

DOCKER_CMD = docker compose
DOCKER_RUN = $(DOCKER_CMD) run -u $$(id -u):$$(id -g) --rm
DOCKER_EXEC = $(DOCKER_CMD) exec -u $$(id -u):$$(id -g)

help: ## This help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

console-php: ## Run php bash
	$(DOCKER_EXEC) php-fpm bash

docs: ## Update docs
	$(DOCKER_EXEC) php-fpm ./vendor/bin/openapi ./app -o /application/public/swagger.json

composer-command: ## Composer command
	$(DOCKER_RUN) composer $(c)

dumpautoload: ## Composer dump autoload
	$(DOCKER_RUN) composer dumpautoload -o

up: ## Up Docker-project
	$(DOCKER_CMD) up -d

down: ## Down Docker-project
	$(DOCKER_CMD) down --remove-orphans

stop: ## Stop Docker-project
	$(DOCKER_CMD) stop

build: ## Build Docker-project
	$(DOCKER_CMD) build --no-cache

ps: ## Show list containers
	$(DOCKER_CMD) ps

log: ## Show containers logs
	$(DOCKER_CMD) logs -f -n10

default: help
