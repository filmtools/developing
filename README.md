# FilmTools · Developing

**PHP classes for film developings**

[![Build Status](https://travis-ci.org/filmtools/developing.svg?branch=master)](https://travis-ci.org/filmtools/developing)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/filmtools/developing/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/filmtools/developing/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/filmtools/developing/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/filmtools/developing/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/filmtools/developing/badges/build.png?b=master)](https://scrutinizer-ci.com/g/filmtools/developing/build-status/master)



## Installation

```bash
$ composer require filmtools/developing
```

This library requires the **[filmtools/commons](https://packagist.org/packages/filmtools/commons)** library as well as **psr/container**.



## The Developing class

The **Developing** class aggregates what makes a film developing: an exposure series, a developing time, and negative densities.

```php
<?php
use FilmTools\Developing\Developing;

$exposures = array( 0, 0.3, 0.6, 0.9);
$densities = array( 0, 0.1, 0.4, 0.6);
$dev_time = 600;

$developing = new Developing( $exposures, $densities, $dev_time);
```
The constructor not only accepts exposure and densities arrays. It also accepts the **Exposures** variations or **Densities** objects from the **[filmtools/commons](https://packagist.org/packages/filmtools/commons)** library:

```php
use FilmTools\Commons\Exposures;
use FilmTools\Commons\Zones;
use FilmTools\Commons\FStops;
use FilmTools\Commons\Densities;

$exposures = new Exposure 0, 0.3, 0.6, 0.9 ]);
$exposures = new Zones([ 0, 1, 2, 3 ]);
$exposures = new FStops([ -5, -4, -3, -2 ]);
$densities = new Densities([  0, 0.1, 0.4, 0.6 ]);

$developing = new Developing( $exposures, $densities, 600);
```



### Methods API

The **Developing** class implements **DensitiesProviderInterface** which itself extends from *ExposuresProviderInterface* and *DensitiesProviderInterface*, both from the **[filmtools/commons](https://packagist.org/packages/filmtools/commons)** library. 

```php
use FilmTools\Commons\Exposures;
use FilmTools\Commons\Densities;

// Returns "Exposures" instance
public function getExposures() : ExposuresInterface;

// Returns "Densities" instance
public function getDensities() : DensitiesInterface;

// Returns the developing time.
public function getTime() : int;
```

*DensitiesProviderInterface* additionally extends from ***\Psr\Container\ContainerInterface***, **\Countable**, and **\IteratorAggregate.**

```php
// Countable
echo count($developing); // 4
```

```php
// IteratorAggregate
foreach( $developing as $logH => $logD):
// noop
endforeach;
```

```php
use Psr\Container\NotFoundExceptionInterface;
use FilmTools\Developing\ExposureNotFoundException;

// ContainerInterface
try {
  $bool = $developing->has( 99 ); // false
  $logD = $developing->get( 99 ); // FALSE  
}
catch (NotFoundExceptionInterface $e)
{
  echo get_class($e); // ExposureNotFoundException
}
```



## The DevelopingFactory 

This callable class builds a new *Developing* instance from an associative Array. The constructor optionally accepts any FQDN of a class that extends from *Developing*.

```php
class MyClass extends Developing
{ ... }

$developing_factory = new DevelopingFactory;
$developing_factory = new DevelopingFactory( MyClass::class );

$developing = $developing_factory([
	'time' => 600,
	'exposures' => [ 0, 0.3, 0.6, 0.9 ],
	'densities' => [ 0, 0.1, 0.4, 0.6 ],
]);
```

In case that you're not dealing with exposure values but *zone numbers* or *f-stops* rather, pass these instead. They will be converted to exposure values internally:

```php
$time = 600;
$densities = [ 0, 0.1, 0.4, 0.6 ];

$developing = $developing_factory([
	'time'      => $time,
	'densities' => $densities,
	'zones'     => [ 0, 1, 2, 3 ],  
]);

$developing = $developing_factory([
	'time'      => $time,
	'densities' => $densities,
	'fstops'    => [ -5, -4, 0, 1, 3 ]
]);
```

### About field names

Allowed field names for **Density** values are `logD`, `density`, and `densities`.

Allowed field names for **Exposure** values are `logH`, `exposure`, and `exposures`.

Allowed field names for **fstops** values are `fstop` and `fstops`.

Allowed field names for **zone numbers** values are `zone` and `zones`.

Allowed field names for **time** values are `seconds` and `time`.

*The most specific column will be used.*



## Deprecation notes

The *Developing* class implements the **getData** method as prescribed by *DevelopingInterface*. This method is deprecated and will be removed with next major release.



## Development and Unit testing

```bash
$ git clone https://github.com/filmtools/developing.git
$ cd developing
$ composer install

# either, or, and:
$ composer test
$ vendor/bin/phpunit
```


