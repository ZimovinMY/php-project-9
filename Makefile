PORT ?= 8000

start:
	PHP_CLI_SERVER_WORKERS=5 php -S 0.0.0.0:$(PORT) -t public

lint:
	XDEBUG_MODE=off vendor/bin/phpcs --standard=PSR12 public/ src/

setup:
	composer install