.PHONY: build up down logs shell migrate fresh deploy cache

# Development
build:
	docker-compose build

up:
	docker-compose up -d

down:
	docker-compose down

logs:
	docker-compose logs -f app

shell:
	docker-compose exec app sh

# Laravel
migrate:
	docker-compose exec app php artisan migrate

fresh:
	docker-compose exec app php artisan migrate:fresh --seed

cache:
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan config:cache

# Prod deployment
deploy:
	gcloud run deploy torre-talent-explorer \
		--source . \
		--region us-central1 \
		--allow-unauthenticated
