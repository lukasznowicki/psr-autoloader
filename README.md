# PSR Autoloader
PSR-4 compatible Autoloader.
I split this project into few classes, to preserve backward compatibility with previous PHP versions. I use 7.4 mostly all of the time, but I realize, that there are many servers with the outdated PHP versions. And since I use this class to my WordPress plugin/theme projects, I decided to use older versions as well.
You may find desired versions in the proper directories. At this time, you may find four versions, for PHP 5.6, 7.0, 7.1 and 7.4. Of course those versions are upward compatible, so you may use 5.6 version running it with PHP 7.4.

Some example codes:
```php
require_once __DIR__ . DIRECTORY_SEPARATOR;
$theAutoloader = new \Phylax\Autoloader();
$theAutoloader->registerHandler();
$theAutoloader->addNamespace( 'Phylax', __DIR__ . DIRECTORY_SEPARATOR . 'Vendors' . DIRECTORY_SEPARATOR . 'Phylax' );
$theAutoloader->addNamespace( 'OtherStuff', __DIR__ . DIRECTORY_SEPARATOR . 'Vendors' . DIRECTORY_SEPARATOR . 'OtherStuff' );
# etc.
```
Or:
```php
require_once __DIR__ . DIRECTORY_SEPARATOR;
new \Phylax\Autoloader( 'OtherStuff', __DIR__ . DIRECTORY_SEPARATOR . 'Vendors' . DIRECTORY_SEPARATOR . 'OtherStuff' );
```

Of course it is always good to check, if class is/is not available. It's possible, e.g. in a WordPress environment where you can find many plugins and themes.
For example:

```php
if ( ! class_exists( 'Phylax\Autoloader' ) ) {
	require_once __DIR__ . DIRECTORY_SEPARATOR;
}
new \Phylax\Autoloader( 'OtherStuff', __DIR__ . DIRECTORY_SEPARATOR . 'Vendors' . DIRECTORY_SEPARATOR . 'OtherStuff' );
```

## Author and featured sites:
* Łukasz Nowicki <https://lukasznowicki.info/> it's my blog, with some WordPress/WooCommerce tips
* [Kurs programowania WordPress](https://wpkurs.pl/) it's my WordPress developing course, in polish
* [Strony internetowe, aplikacje](https://phylax.pl/) and this is my company's page.

## License
Copyright 2016-2020 phylax.pl Łukasz Nowicki <https://phylax.pl/>
Licensed under the GPLv2 or later: <http://www.gnu.org/licenses/gpl-2.0.html>