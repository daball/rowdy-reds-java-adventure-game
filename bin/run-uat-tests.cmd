@echo off
php ..\vendor\phpunit\phpunit\phpunit --colors --verbose --configuration ..\phpunit.xml --testsuite uat --coverage-html ..\test-results\uat
