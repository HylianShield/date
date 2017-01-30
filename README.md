# Introduction

The business logic abstraction layer for your date sensitive application.

## Installation

```shell
composer require hylianshield/date:^1.0
```

## Usage

The date container is an easy to understand container that can store data alongside full-fledged instances of `DateTimeInterface`.
It uses instances of `DateTimeInterface` as keys and user supplied data as corresponding value.

It achieves this by internally using the date storage entity, which stores date objects as keys with a precision determined by a date format.

### Creating a date container

One may use the factory to create a new date container. This is the easiest approach with the least required configuration.

```php
<?php
$factory = new HylianShield\Date\DateContainerFactory();

// Create a date container with a precision of whole days.
$container = $factory->createIntervalDay();
```

#### Using a custom storage

The date storage is what internally stores the dates and corresponding data into memory.
It needs both a date format and an instance of `DateTimeZone` to be properly configured.

```php
<?php
$storage = new HylianShield\Date\DateStorage(
    'Y-m-d',
    new DateTimeZone('Europe/Amsterdam')
);
$container = new HylianShield\Date\DateContainer($storage);
```

The supplied date format is used to uniquely identify date objects.
To prevent dates from different time zones to pollute our business logic,
the storage only accepts date instances that match the given time zone.

### Populating the container

The example code below is just that. Example code.
Normally, this would probably be populated by iterating over a data source.

We use a reference date, right in the middle of our date period.

```php
<?php
$today = new DateTime('today');
```

And we'll construct a date period with that.

```php
<?php
/** @var DateTime $today */
$start = clone $today;
$start->modify('-1 day');

$end = clone $today;
$end->modify('+2 days');

// Interval of 1 day.
$interval = new DateInterval('P1D');

$period = new DatePeriod($start, $interval, $end);
```

Using that, we populate the container with dummy data.

```php
<?php
/**
 * @var DatePeriod $period 
 * @var \HylianShield\Date\DateContainerInterface $container
 */
foreach ($period as $date) {
    $container->attach($date, 'foo');
}
```

Now we can check if today is stored in the container:

```php
<?php
/**
 * @var DateTimeInterface $today
 * @var \HylianShield\Date\DateContainerInterface $container
 */
var_dump($container->contains($today)); // bool(true)
```

We can explicitly get data for that date.

```php
<?php
/**
 * @var DateTimeInterface $today
 * @var \HylianShield\Date\DateContainerInterface $container
 */
var_dump($container->getData($today)); // string(3) "foo"
```

Overriding the data remains a possibility.

```php
<?php
/**
 * @var DateTimeInterface $today
 * @var \HylianShield\Date\DateContainerInterface $container
 */
$container->attach($today, 'bar');
var_dump($container->getData($today)); // string(3) "bar"
```

### Iterating the container

The real power of the container lies in the fact that you have full `DateTimeInterface` instances as the keys of your container.

```php
<?php
/** @var \HylianShield\Date\DateContainerInterface $container */
foreach ($container as $date => $userData) {
    var_dump(
        $date->format('Y-m-d H:i:s'),
        $userData
    );
}
```

This outputs something like:

```
string(19) "2016-04-09 00:00:00"
string(3) "foo"
string(19) "2016-04-10 00:00:00"
string(3) "bar"
string(19) "2016-04-11 00:00:00"
string(3) "foo"
```

### Exporting the container

To easily export the data inside the container, it exposes a method to convert to a PHP array.

```php
<?php
/** @var \HylianShield\Date\DateContainerInterface $container */
var_dump($container->toArray());
```

This will output something like:

```
array(3) {
  ["2016-04-09"]=>
  string(3) "foo"
  ["2016-04-10"]=>
  string(3) "bar"
  ["2016-04-11"]=>
  string(3) "foo"
}
```

The keys used here will correspond with the format as configured on the date storage.

### Detaching dates

When detaching a date, the date and all corresponding data disappears:

```php
<?php
/**
 * @var DateTimeInterface $today
 * @var \HylianShield\Date\DateContainerInterface $container
 */
$container->detach($today);
var_dump(
    $container->contains($today),
    $container->getData($today),
    $container->toArray()
);
```

Outputs something like:

```
bool(false)
NULL
array(2) {
  ["2016-04-09"]=>
  string(3) "foo"
  ["2016-04-11"]=>
  string(3) "foo"
}
```

### Setting an illegal date

When mixing dates from different time zones, your business logic gets skewed.
To prevent this, the date storage does not allow mixing of time zones.

```php
<?php
/** @var \HylianShield\Date\DateContainerInterface $container */
$illegalDate = new DateTime('now', new DateTimeZone('UTC'));
$container->attach($illegalDate, 'baz');
```

Will render the following:

```
PHP Fatal error:  Uncaught exception 'HylianShield\Date\IllegalDateTimeZoneException' with message 'Date storage expects date in time zone Europe/Amsterdam, yet received UTC'
```
