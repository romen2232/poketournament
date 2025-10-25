# ---------------------------
# Paths / Containers
# ---------------------------
CERT_DIR = infra/nginx/certs
BACKEND = php
FRONTEND = node

# ---------------------------
# Docker lifecycle
# ---------------------------
up:
	docker compose up -d

down:
	docker compose down

restart:
	docker compose down
	docker compose up -d

build:
	docker compose up --build -d

logs:
	docker compose logs -f

clean:
	docker compose down -v --remove-orphans

ps:
	docker compose ps

# ---------------------------
# First-time setup
# ---------------------------
setup: certs build

certs:
	mkdir -p $(CERT_DIR)
	@if [ ! -f $(CERT_DIR)/poketournament.local.crt ]; then \
		echo "👉 Creating SSL certificate for poketournament.local"; \
		openssl req -x509 -nodes -newkey rsa:2048 -days 3650 \
		  -keyout $(CERT_DIR)/poketournament.local.key \
		  -out $(CERT_DIR)/poketournament.local.crt \
		  -subj "/C=XX/ST=Local/L=Local/O=Dev/OU=Dev/CN=poketournament.local"; \
	fi
	@if [ ! -f $(CERT_DIR)/api.poketournament.local.crt ]; then \
		echo "👉 Creating SSL certificate for api.poketournament.local"; \
		openssl req -x509 -nodes -newkey rsa:2048 -days 3650 \
		  -keyout $(CERT_DIR)/api.poketournament.local.key \
		  -out $(CERT_DIR)/api.poketournament.local.crt \
		  -subj "/C=XX/ST=Local/L=Local/O=Dev/OU=Dev/CN=api.poketournament.local"; \
	fi
	@echo "✅ Certificates created (remember to trust them locally one time!)"

# ---------------------------
# Backend (Symfony)
# ---------------------------
bash-backend:
	docker compose exec -it $(BACKEND) bash

symfony:
	docker compose exec -it $(BACKEND) php bin/console $(c)

composer:
	docker compose exec -it $(BACKEND) composer $(c)

# ---------------------------
# Frontend (Next.js)
# ---------------------------
bash-frontend:
	docker compose exec -it $(FRONTEND) sh

npm:
	docker compose exec -it $(FRONTEND) npm $(c)
