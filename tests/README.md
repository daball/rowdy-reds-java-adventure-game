## Selenium Installation

First, install the Selenium Server:

1. Download a distribution archive of Selenium Server.
2. Unzip the distribution archive and copy `selenium-server-standalone-2.45.0.jar` (check the version suffix) to `/usr/local/bin`, for instance.
3. Start the Selenium Server server by running `java -jar /usr/local/bin/selenium-server-standalone-2.45.0.jar`.


## Selenium Tests

uat.php
  : User Acceptance Tests - system-level tests that ensure that application passes tests required to ensure correct user story functionality

## Running Selenium Tests

uat.php
  : `../vendor/phpunit/phpunit/phpunit --colors --verbose --configuration ../phpunit.xml`
