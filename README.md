[![Latest Stable Version](https://img.shields.io/packagist/v/gpslab/interval.svg?maxAge=3600&label=stable)](https://packagist.org/packages/gpslab/interval)
[![Latest Unstable Version](https://img.shields.io/packagist/vpre/gpslab/interval.svg?maxAge=3600&label=unstable)](https://packagist.org/packages/gpslab/interval)
[![Total Downloads](https://img.shields.io/packagist/dt/gpslab/interval.svg?maxAge=3600)](https://packagist.org/packages/gpslab/interval)
[![Build Status](https://img.shields.io/travis/gpslab/interval.svg?maxAge=3600)](https://travis-ci.org/gpslab/interval)
[![Coverage Status](https://img.shields.io/coveralls/gpslab/interval.svg?maxAge=3600)](https://coveralls.io/github/gpslab/interval?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/gpslab/interval.svg?maxAge=3600)](https://scrutinizer-ci.com/g/gpslab/interval/?branch=master)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/74587b70-e6e4-42b4-93c2-f1bef103bde9.svg?maxAge=3600&label=SLInsight)](https://insight.sensiolabs.com/projects/74587b70-e6e4-42b4-93c2-f1bef103bde9)
[![StyleCI](https://styleci.io/repos/75078831/shield?branch=master)](https://styleci.io/repos/75078831)
[![License](https://img.shields.io/packagist/l/gpslab/interval.svg?maxAge=3600)](https://github.com/gpslab/interval)

Interval Value Objects
======================

This is a library with a set of classes intended to describe intervals as
[Value Objects](https://en.wikipedia.org/wiki/Value_object) and perform operations on them.

## Installation

Pretty simple with [Composer](http://packagist.org), run:

```sh
composer require gpslab/interval
```

## Interval type

This library is supports [interval types](https://en.wikipedia.org/wiki/Interval_(mathematics)).

* `[a, b]` - Closed
* `[a, b)` - Half-closed
* `(a, b]` - Half-open
* `(a, b)` - Open

## Intervals

* Number
* Date
* Time
* DateTime
* Week
* Month
* Year
* IPv4
* IPv6
* IPv4Network
* IPv6Network


## Usage intervals

Create interval `[1, 5)`

```php
$interval = NumberInterval::halfClosed(1, 5);
echo $interval->start(); // 1
echo $interval->end(); // 5

// convert to string
$string = (string)$interval;
echo $string; // [1, 5)

// restore from string
$new_interval = NumberInterval::fromString($string);
$interval == $new_interval; // true
$interval->equal($new_interval); // true
```

Change intervals

```php
$interval = NumberInterval::halfClosed(1, 5);
// created a new interval instance
$new_interval = $interval->withStart(2);
$interval->start() != $new_interval->start(); // true
```

IPv4 network

```php
// from CIDR
$network = IPv4Network::fromCIDR('192.168.0.0', 16);
echo $network->start(); // 192.168.0.0
echo $network->end(); // 192.168.255.255

$network->contains('192.168.13.74'); // true

// from ip mask
$new_network = IPv4Network::fromMask('192.168.0.0', '255.255.0.0');
$network->equal($new_network); // true
```

## Interval operations

* `equal` - Checks if this Interval is equal to the specified interval;
* `contains` - Does this interval contain the specified point;
* `intersects` - Does this interval intersect the specified interval;
* `intersection` - Gets the intersection between this interval and another interval;
* `cover` - Gets the covered interval between this Interval and another interval;
* `gap` - Gets the gap between this interval and another interval;
* `abuts` - Does this interval abut with the interval specified;
* `join` - Joins the interval between the adjacent;
* `union` - Gets the union between this interval and another interval;
* `before` - The point is before the interval;
* `after` - The point is after the interval.

## Iterate intervals

The following intervals support iteration:

* Number
* Date
* Time
* DateTime
* Week
* Month
* Year
* IPv4
* IPv4Network

### Example usage

Use step `1` and closed interval type `[1, 5]`

```php
$interval = NumberInterval::closed(1, 5);

$points_in_interval = [];
foreach ($interval->iterate() as $point) {
    $points_in_interval[] = $point;
}

$points_in_interval == [1, 2, 3, 4, 5]; // true
```

Use step `2` and open interval type `[0, 10]`

```php
$step = 2;
$interval = NumberInterval::open(0, 10);

$points_in_interval = [];
foreach ($interval->iterate($step) as $point) {
    $points_in_interval[] = $point;
}

$points_in_interval == [2, 4, 6, 8]; // true
```

Iterate IPv4 interval

```php
$expected = [
    '10.0.1.2',
    '10.0.1.4',
    '10.0.1.6',
    '10.0.1.8'
];
$step = 2;
$interval = IPv4Interval::open('10.0.1.0', '10.0.1.10');

$points_in_interval = [];
foreach ($interval->iterate($step) as $point) {
    $points_in_interval[] = $point;
}
$points_in_interval == $expected; // true
```

Iterate date interval

```php
$expected = [
    '2017-03-03',
    '2017-03-05',
    '2017-03-07',
    '2017-03-09',
];
$step = new \DateInterval('P2D');
$interval = DateInterval::open(new \DateTime('2017-03-01'), new \DateTime('2017-03-11'));

$points_in_interval = [];
foreach ($interval->iterate($step) as $point) {
    $points_in_interval[] = $point->format('Y-m-d');
}

$points_in_interval == $expected; // true
```

## Persistence in Doctrine

You cat use intervals as
[Custom Mapping Types](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/cookbook/custom-mapping-types.html)
for Doctrine.

```php
Type::addType('NumberInterval', 'GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\NumberIntervalType');
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('NumberInterval', 'NumberInterval');
```

Example registration Doctrine types in Symfony

```yml
# app/config/config.yml
doctrine:
    dbal:
        types:
            NumberInterval: GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\NumberIntervalType
            DateInterval: GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\DateIntervalType
            TimeInterval: GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\TimeIntervalType
            DateTimeInterval: GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\DateTimeIntervalType
            WeekInterval: GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\WeekIntervalType
            MonthInterval: GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\MonthIntervalType
            YearInterval: GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\YearIntervalType
            IPv4Interval: GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\IPv4IntervalType
            IPv6Interval: GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\IPv6IntervalType
            IPv4Network: GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\IPv4NetworkType
            IPv6Network: GpsLab\Component\Interval\Persistence\Doctrine\DBAL\Types\IPv6NetworkType
```

## License

This bundle is under the [MIT license](http://opensource.org/licenses/MIT). See the complete license in the file: LICENSE
