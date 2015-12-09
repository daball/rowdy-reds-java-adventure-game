CHANGELOG
-----------
### v2.1.0, 2015.09.30

* Added support for the missing `__METHOD__`, `__FUNCTION__` and `__TRAIT__` magic constants
* Added some security related classes and interfaces: `Opis\Closure\SecurityProviderInterface`,
`Opis\Closure\DefaultSecurityProvider`, `Opis\Closure\SecureClosure`, `Opis\Closure\SecurityException`.
* Fiexed a bug in `Opis\Closure\ReflectionClosure::getClasses` method
* Other minor bugfixes
* Added support for static closures
* Added public `isStatic` method to `Opis\Closure\ReflectionClosure` class


### v2.0.1, 2015.09.23

* Removed `branch-alias` property from `composer.json`
* Bugfix. See [issue #6](https://github.com/opis/closure/issues/6)

### v2.0.0, 2015.07.31

* The closure parser was improved
* Class names are now automatically resolved
* Added support for the `#trackme` directive which allows tracking closure's residing source

### v1.3.0, 2014.10.18

* Added autoload file
* Changed README file

### Opis Closure 1.2.2

* Started changelog
