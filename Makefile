.PHONY: build up down logs shell migrate fresh deploy cache
.PHONY: build-mariadb up-mariadb down-mariadb logs-mariadb shell-mariadb migrate-mariadb fresh-mariadb cache-mariadb
.PHONY: build-sqlite up-sqlite down-sqlite logs-sqlite shell-sqlite migrate-sqlite fresh-sqlite cache-sqlite

# Default profile
build:
	docker-compose --profile sqlite build

up:
	docker-compose --profile sqlite up -d

down:
	docker-compose --profile sqlite down

logs:
	docker-compose logs -f torre-talent-explorer-sqlite

shell:
	docker-compose exec torre-talent-explorer-sqlite sh

migrate:
	docker-compose exec torre-talent-explorer-sqlite php artisan migrate

fresh:
	docker-compose exec torre-talent-explorer-sqlite php artisan migrate:fresh --seed

cache:
	docker-compose exec torre-talent-explorer-sqlite php artisan cache:clear
	docker-compose exec torre-talent-explorer-sqlite php artisan config:cache

# MariaDB
build-mariadb:
	docker-compose --profile mariadb build

up-mariadb:
	docker-compose --profile mariadb up -d

down-mariadb:
	docker-compose --profile mariadb down

logs-mariadb:
	docker-compose logs -f torre-talent-explorer-mariadb

shell-mariadb:
	docker-compose exec torre-talent-explorer-mariadb sh

migrate-mariadb:
	docker-compose exec torre-talent-explorer-mariadb php artisan migrate

fresh-mariadb:
	docker-compose exec torre-talent-explorer-mariadb php artisan migrate:fresh --seed

cache-mariadb:
	docker-compose exec torre-talent-explorer-mariadb php artisan cache:clear
	docker-compose exec torre-talent-explorer-mariadb php artisan config:cache

# SQLite
build-sqlite:
	docker-compose --profile sqlite build

up-sqlite:
	docker-compose --profile sqlite up -d

down-sqlite:
	docker-compose --profile sqlite down

logs-sqlite:
	docker-compose logs -f torre-talent-explorer-sqlite

shell-sqlite:
	docker-compose exec torre-talent-explorer-sqlite sh

migrate-sqlite:
	docker-compose exec torre-talent-explorer-sqlite php artisan migrate

fresh-sqlite:
	docker-compose exec torre-talent-explorer-sqlite php artisan migrate:fresh --seed

cache-sqlite:
	docker-compose exec torre-talent-explorer-sqlite php artisan cache:clear
	docker-compose exec torre-talent-explorer-sqlite php artisan config:cache

deploy:
	gcloud run deploy torre-talent-explorer-app \
		--source . \
		--region us-central1 \
		--allow-unauthenticated