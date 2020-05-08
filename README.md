# PSR Autoloader
I wrote small PSR-4 Autoloader to use with my projects. Feel free to use it in yours.

###### Warning: This class uses PHP version _7.4_ syntax

Some example codes:

```php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Vendors' . DIRECTORY_SEPARATOR . 'Phylax' . DIRECTORY_SEPARATOR . 'Autoloader.php';
$theAutoloader = new \Phylax\Autoloader();
$theAutoloader->registerHandler();
$theAutoloader->addNamespace( 'Phylax', __DIR__ . DIRECTORY_SEPARATOR . 'Vendors' . DIRECTORY_SEPARATOR . 'Phylax' );
$theAutoloader->addNamespace( 'OtherStuff', __DIR__ . DIRECTORY_SEPARATOR . 'Vendors' . DIRECTORY_SEPARATOR . 'OtherStuff' );
# etc.
```

Or:
```php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Vendors' . DIRECTORY_SEPARATOR . 'Phylax' . DIRECTORY_SEPARATOR . 'Autoloader.php';
new \Phylax\Autoloader( 'OtherStuff', __DIR__ . DIRECTORY_SEPARATOR . 'Vendors' . DIRECTORY_SEPARATOR . 'OtherStuff' );
```

Of course it is always good to check, if class is/is not available. It's possible, e.g. in a WordPress environment where you can find many plugins and themes.
For example:

```php
if ( ! class_exists( 'Phylax\Autoloader' ) ) {
	require_once __DIR__ . DIRECTORY_SEPARATOR . 'Vendors' . DIRECTORY_SEPARATOR . 'Phylax' . DIRECTORY_SEPARATOR . 'Autoloader.php';
}
new \Phylax\Autoloader( 'OtherStuff', __DIR__ . DIRECTORY_SEPARATOR . 'Vendors' . DIRECTORY_SEPARATOR . 'OtherStuff' );
```

## Authors
* Łukasz Nowicki <https://lukasznowicki.info/>
* [Kurs programowania WordPress](https://wpkurs.pl/)
* [Strony internetowe, aplikacje](https://phylax.pl/)



## License
Copyright 2016 phylax.pl Łukasz Nowicki
<https://phylax.pl/>

Licensed under the GPLv2 or later: <http://www.gnu.org/licenses/gpl-2.0.html>
