# caridea-filter
Caridea is a miniscule PHP application library. This shrimpy fellow is what you'd use when you just want some helping hands and not a full-blown framework.

![](http://libreworks.com/caridea-100.png)

This is its value sanitation library.

[![Packagist](https://img.shields.io/packagist/v/caridea/filter.svg)](https://packagist.org/packages/caridea/filter)
[![Build Status](https://travis-ci.org/libreworks/caridea-filter.svg)](https://travis-ci.org/libreworks/caridea-filter)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/libreworks/caridea-filter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/libreworks/caridea-filter/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/libreworks/caridea-filter/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/libreworks/caridea-filter/?branch=master)
[![Documentation Status](http://readthedocs.org/projects/caridea-filter/badge/?version=latest)](http://caridea-filter.readthedocs.io/en/latest/?badge=latest)

## Installation

You can install this library using Composer:

```console
$ composer require caridea/filter
```

* The master branch (version 3.x) of this project requires PHP 7.1 and the `mbstring` extension.
* Version 2.x of this project requires PHP 7.0 and the `mbstring` extension.

## Compliance

Releases of this library will conform to [Semantic Versioning](http://semver.org).

Our code is intended to comply with [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/), and [PSR-4](http://www.php-fig.org/psr/psr-4/). If you find any issues related to standards compliance, please send a pull request!

## Documentation

* Head over to [Read the Docs](http://caridea-filter.readthedocs.io/en/latest/)

## Examples

Just a few quick examples. Let's define a set of filters for a person record.

```php
// let's pretend this came from $_POST
$input = [
    'name' => 'john smith  ',
    'birthday' => '1990-01-01__',
    'bio' => "Mistakenly written on Windows\r\nThat's a problem.  ",
    'friends' => 'Jane'
];

$registry = new \Caridea\Filter\Registry(); // you can register your own filters if you choose
$b = $registry->builder();
$b->always('name')->then('trim')->then('titlecase'); // always() will run chain even if missing from input
$b->field('birthday')->then('regex', '/[^0-9-]/', '');
$b->field('bio')->then('trim')->then('nl'); // convert to UNIX newlines
$b->always('species')->then('default', 'Homo sapiens');
$b->field('friends')->then('array')->each('trim'); // each() will run the filter on every element
// by default, all fields you don't specify are dropped.
// but! otherwise() can specify a fallback chain for any non-declared fields.
// $b->otherwise('trim')->then('default', null);
$filter = $b->build();
$output = $filter($input);
var_dump($output);
```
```
array(5) {
  'name' =>
  string(10) "John Smith"
  'birthday' =>
  string(10) "1990-01-01"
  'bio' =>
  string(47) "Mistakenly written on Windows
That's a problem."
  'species' =>
  string(12) "Homo sapiens"
  'friends' =>
  array(1) {
    [0] =>
    string(4) "Jane"
  }
}
```

You can also supply one or more `Reducer`s which are intended to combine and rewrite multiple values at once.

```php
// let's pretend this came from $_POST
$input = [
    'username' => '  doublecompile  ',
    'id-0' => '1',
    'id-5' => '4',
    'id-1' => '9'
];

$registry = new \Caridea\Filter\Registry();
$b = $registry->builder();
$b->always('username')->then('trim');
$b->reducer(Combiners::appender('ids', 'id-'));
$filter = $b->build();
$output = $filter($input);
var_dump($output);
```
```
array(2) {
  'username' =>
  string(13) "doublecompile"
  'ids' =>
  array(3) {
    [0] =>
    string(1) "1"
    [1] =>
    string(1) "4"
    [2] =>
    string(1) "9"
  }
}
```
The `Filter` class itself is a `Reducer`.
