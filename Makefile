# Makefile

setup:
	npm install
	composer install
	php artisan storage:link
	php artisan migrate
	php artisan key:generate
.PHONY: setup
