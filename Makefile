init:
	npm install
	composer install

env:
	cp .env.example .env

up:
	./vendor/bin/sail up -d --build

migrate:
	./vendor/bin/sail exec laravel.test php artisan migrate --seed

start:
	npm run dev

down:
	./vendor/bin/sail down
