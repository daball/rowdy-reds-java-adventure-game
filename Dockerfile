FROM composer/composer:latest
RUN $(pwd):/app composer/composer install
FROM phpunit/phpunit:latest
RUN $(pwd):/app phpunit/phpunit run
