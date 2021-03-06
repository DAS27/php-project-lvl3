start:
	php artisan serve --host 0.0.0.0

setup:
	composer install
	cp -n .env.example .env|| true
	cp .env .env.testing|| true
	php artisan key:gen --ansi
	touch database/database.sqlite||true
	php artisan migrate

migrate:
	php artisan migrate

console:
	php artisan tinker

log:
	tail -f storage/logs/laravel.log

test:
	php artisan test --env=tesing

test-coverage:
	composer phpunit tests -- --coverage-clover build/logs/clover.xml

deploy:
	git push heroku

lint:
	composer phpcs

lint-fix:
	composer phpcbf

