FROM composer/composer:latest
RUN $(pwd):/app composer/composer install
FROM phpunit/phpunit:latest
RUN phpunit --colors --verbose --configuration $(pwd)/Game/phpunit.xml --testsuite unit --coverage-html $(pwd)/Game/test-results/unit

