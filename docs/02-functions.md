# Predefined and Custom Sanitation Functions

This chapter will detail all of the available sanitation functions predefined in the `Registry`, as well as the means by which you can construct and register your own functions.

## Provided Functions

The `Registry` has a number of functions that you can use out of the box.

* `trim` – Removes whitespace from the beginning and end of a string
* `lowercase` – Safely converts a unicode string to lowercase
* `uppercase` – Safely converts a unicode string to uppercase
* `titlecase` – Safely converts a unicode string to title case
* `string` – Safely converts a value to a string
* `replace` – Performs a search and replace on a string, takes the string to locate and the replacement as arguments (e.g. `->then('replace', 'search-for', 'replace-with')`)
* `regex` – Performs a regular expression search and replace on a string, takes the pattern and replacement as arguments (e.g. `->then('regex', '/[0-9]/', 'replace-with')`)
* `cut` – Truncates a string to a certain length, takes the length as an argument (e.g. `->then('cut', 20)`)
* `alnum` – Removes any characters not defined as being alphanumeric; essentially the same thing as using `regex` with the pattern `/[^\p{L}\p{Nd}]/u`
* `alpha` – Removes any characters not defined as being alphabetic; essentially the same thing as using `regex` with the pattern `/[^\p{L}]/u`
* `numeric` – Removes any characters not defined as being numeric; essentially the same thing as using `regex` with the pattern `/[^\p{N}]/u`
* `nl` – Turns all manner of newlines (e.g. Windows) into UNIX-style
* `compactnl` – Cleans up a string, replacing two or more UNIX newlines (i.e. `\n`) with just two (e.g. `\n\n\n\n\n` would become `\n\n`)
* `bool` – Turns any _truthy_ string value (i.e. `"yes"`, `"y"`, `"on"`, `"1"`, `"true"`, `"t"`) into `true`, anything else into `false`
* `int` – Turns any string value into an integer, possibly `0`
* `float` – Turns any string value into a float, possibly `0.0`
* `array` – Turns a value into an array; `array`s remain as-is, `Traversable` values go through `iterator_to_array`, and scalar values become arrays with a single scalar entry
* `default` – Any `null` or empty string will return the default argument you provide (e.g. `->then('default', 'N/A')`)
* `split` – Splits a string into an array, takes the pattern as an argument (e.g. `->then('split', '/[-_\.,]/')`)
* `explode` – Splits a string into an array, takes the separator as an argument (e.g. `->then('explode', '-')`)
* `join` – Joins an array into a string, takes the separator as an argument (e.g. `->then('join', '_')`)
* `slice` – Takes the first `n` entries from an array, takes the length as an argument (e.g. `->then('slice', 5)`)

## Custom Functions

The `Registry` class has a `register` method that accepts an `array`. By invoking this method, you can register your own custom sanitation functions. The array keys are the names of your functions, and each value should be a `callable` factory that accepts zero or more arguments and produces a sanitation function. A sanitation function itself must be a `callable` that accepts a single argument and returns a single value.

```php

class MyGzip
{
    public static function compressor(): \Closure
    {
        return function ($data) {
            return gzcompress($data);
        };
    }
}

function function_that_returns_a_closure(): \Closure
{
    return function ($value) {
        return str_rot13($value);
    };
}

class Hasher
{
    private $algo;

    public function __construct(string $algo)
    {
        $this->algo = $algo;
    }

    public function __invoke($value): string
    {
        return password_hash($value, $this->algo);
    }
}

$registry = new \Caridea\Filter\Registry();
$registry->register([
    'gzip' => ['MyGzip', 'compressor'],
    'password' => function ($algo) { return new Hasher($algo); },
    'something' => 'my_function_that_returns_a_closure'
]);

$b = $registry->builder();
$b->field('password')->then('password', PASSWORD_DEFAULT);
$b->field('body')->then('gzip');
$b->field('message')->then('something');
```

The factories that you register with the `Registry` really need to be  immutable; sanitation functions produced for one `Chain` should have no bearing on sanitation functions produced for another.
