@echo off
REM
SETLOCAL
SET SUITE=unit
IF .%1 == . (
  SET SUITE=%*
)
php ..\vendor\phpunit\phpunit\phpunit --colors --verbose --configuration ..\phpunit.xml --testsuite "%SUITE%" --coverage-html ..\public\test-results\unit
