# The Registry and the Filter

The `Caridea\Filter\Registry` class is the face of this library. You can use it to register your own sanitation functions (more on that [later](02-functions.md)), and you primarily use it to declare your sanitation rules.

```php
$registry = new \Caridea\Filter\Registry();
$b = $registry->builder();
```
## Declaring Rule Chains

An instance of the `Caridea\Filter\Builder` class is what the registry's `builder` method returns. To register sanitation rules, it has two important methods: `always` and `field`.

```php
// always() will run even if missing from input
$b->always('name')->then('trim')->then('titlecase');
$b->always('species')->then('default', 'Homo sapiens');

// field() will run only when the input is present
$b->field('birthday')->then('regex', '/[^0-9-]/', '');
$b->field('bio')->then('trim')->then('nl');
$b->field('friends')->then('array')->each('trim');
```

The `always` and `field` methods return a `Caridea\Filter\Chain` object. While defining your rules, the `Chain` class has two methods that we used in the above example: `then` and `each`.

* The `then` method allows you to specify a rule. You can define several in a row.
* The `each` method allows you to specify a rule that runs on every element of an `array`.

The rules of a `Chain` run in defined order.

See _[Chapter 2: Functions](02-functions.md)_ for a list of predefined filtering functions as well as how you can define your own custom filter functions.

### Non-declared Fields

By default, all fields from the input that you don't declare in the sanitation rules are dropped and removed from the output. _But!_ The `otherwise` method of the `Builder` can specify a fall-back chain for any non-declared fields.

```php
$b->otherwise('trim')->then('default', null);
```

## Retrieve a Filter

Once you have declared all of your sanitation rules, you can use the `Builder` to produce a `Caridea\Filter\Filter` object.

```php
$filter = $b->build(); // get our Filter from the Builder
```

A `Filter` is just an immutable container for all of the sanitation rules you've defined with a `Builder`. When you invoke the `Builder`'s `build` method, the `Chain`s themselves are _cloned_.

Let's say you produce a `Filter`. If you then add additional `Chain`s, or additional rules to a `Chain`, none of these changes would appear in the `Filter` you've already produced. In this way, you can use a single `Builder` to create different `Filter`s that have similar rules.

```php
$registry = new \Caridea\Filter\Registry();
$b = $registry->builder();

// rules for $filter1
$chain = $b->always('foo');
$chain->then('trim');
$filter1 = $b->build();

// rules for $filter2
$chain->then('lowercase');
$b->always('bar')->then('uppercase');
$filter2 = $b->build(); // $filter2 has the new rules; $filter1 does not.
```

### Filtering Input

Let's use the example rules we specified above (at the beginning of the _Declaring Rule Chains_ section) to take some user input and produce some sanitized output. A filter behaves just like an anonymous function (i.e. it has an `__invoke` method).

```php
// let's pretend this came from $_POST
$input = [
    'name' => 'john smith  ',
    'birthday' => '1990-01-01__',
    'bio' => "Mistakenly written on Windows\r\nThat's a problem.  ",
    'friends' => 'Jane'
];

$output = $filter($input);
var_dump($output);
```

And here's our output

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
