# Reducers and Combiners

Thus far, we've only covered sanitation functions that take one value and produce another value to replace it.

What happens when you want to combine several fields into another, or take a single field and divide it into several fields? The answer is by using a `Reducer`. `Reducer`s are meant to alter multiple fields. While the name infers that array fields are reduced, a `Reducer` *could* in fact add additional fields.

The real purpose is to squash several values into a different type. For
example:

* An address `Reducer` could take several values out of the input and return a
  single address object
* Maybe you have a phone number in several fields as country code, area code,
  local, extension, that a `Reducer` could combine into a single phone number object

## The Reducer interface

The `Caridea\Filter\Reducer` interface only has one method: `__invoke`, which accepts a single `array` and returns an `array`. The `Filter` class _itself_ is a `Reducer`.

`Reducer`s are added to a `Builder`. Once you produce a `Filter` and use it to sanitize input, any `Reducer`s you defined are run in the order they were inserted to the `Builder`. Each `Reducer` would then operate on the output of the previous `Reducer`.

## The Combiners class

This library comes with the `Caridea\Filter\Combiners` class, which has three static factory methods:

* `appender` – Takes several fields with a given prefix and combines their values into a list
  * For example, `value1`, `value2`, and `value3` can be combined into an outgoing field `value`, containing an `array` with 3 entries.
* `prefixed` – Takes several fields with a common prefix and turns them into an associative array
  * For example, `address-street` and `address-city` can be combined into an outgoing field `address`, containing an `array` with keys for `street` and `city`.
* `datetime` – Takes several fields and turns them into a `DateTime` object.
  * Its first argument is the destination field name in the output.
  * Its second is the input field name that holds an ISO 8601 date (e.g. `2018-01-01`)
  * Its third is the input field name that holds an ISO 8601 time (e.g. `T01:23:45`)
  * Its optional fourth argument is the field name that holds a timezone identifier (e.g. `America/New_York`).

Let's use the `appender` method in the following example:

```php
$registry = new \Caridea\Filter\Registry();
$b = $registry->builder();
$b->always('username')->then('trim');
$b->reducer(Combiners::appender('ids', 'id-'));

// let's pretend this came from $_POST
$input = [
    'username' => '  foobar  ',
    'id-0' => '1',
    'id-5' => '4',
    'id-1' => '9'
];

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
