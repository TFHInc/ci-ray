# Ray

[![Latest Version on Packagist][ico-version]][link-packagist]
[![PHP Version][ico-php-version]][link-packagist]
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

Ray is an expressive PHP array library for the [Codeigniter](https://codeigniter.com/) framework.

## Requirements

- PHP >= 7.1.0
- CodeIgnitor 3.x

## Installation

```bash
composer require tfhinc/ci-ray
```

Run the post install command to publish the helper and class files to the appropriate CI directories:
```bash
composer --working-dir=vendor/tfhinc/ci-ray/ run-script publish-files
```

## Loading the Library

There are a few available options for loading the Warehouse library:

### Using the `ray()` helper function

The Ray helper function will resolve the `Ray` class via the CI instance. It will either load the class or return the existing class instance:

``` php
$this->load->helper('ray');
```

### Using the Ray Class

The Ray class can be instantiated when you require it:

``` php
$ray = new TFHInc/Ray/Ray();
```

### Using the Ray CI Library

The Ray class can be loaded like any other CI library:

``` php
$this->load->library('Ray');
```

## Usage

`Ray` can be used in a variety of ways to manipulate and transfrom arrays.

### Method Usage

`Ray` enables you to interact with arrays using simple methods that return a single value or a transformed array:

```php
// Given an array...

$fruit = [
    'lemon' => 'yellow',
    'apple' => 'red',
    'lime' => 'green',
    'pear' => 'green',
];

// ...Get all of the keys except 'apple' and 'lime':

ray($fruit)->except(['apple', 'lime'])->toArray();

/*

    [
        'lemon' => 'yellow',
        'pear' => 'green',
    ]


*/

// ...Get the first value:

ray($fruit)->first();

/*

    'yellow'

*/

// ...Sort by value:

ray($fruit)->sortByValues()->toArray();

/*

    [
        'lime' => 'green',
        'pear' => 'green',
        'apple' => 'red',
        'lemon' => 'yellow',
    ]

*/
```

### Method Chaining

The power of `Ray` is displayed when chaining methods together to manipulate an array:

``` php
// Given a multidimensional array...

$fruit_multi = [
    [ 'id' => 1, 'name' => 'lemon', 'color' => 'yellow',    'price' => 2.25, 'qty' => 2 ],
    [ 'id' => 2, 'name' => 'apple', 'color' => 'red',       'price' => 0.99, 'qty' => 12 ],
    [ 'id' => 3, 'name' => 'lime',  'color' => 'green',     'price' => 3.50, 'qty' => 9 ],
    [ 'id' => 4, 'name' => 'pear',  'color' => 'green',     'price' => 2.00, 'qty' => 7 ],
];

// ...Group the array by the 'color' key and only return keys 'red' or 'green':

ray($fruit_multi)->groupBy('color')->only(['red', 'green'])->toArray();

/*
    [
        'red' => [
            [
                'id' => 2,
                'name' => 'apple',
                'color' => 'red',
                'price' => 0.99,
                'qty' => 12,
            ],
        ],
        'green' => [
            [
                'id' => 3,
                'name' => 'lime',
                'color' => 'green',
                'price' => 3.5,
                'qty' => 9,
            ],
            [
                'id' => 4,
                'name' => 'pear',
                'color' => 'green',
                'price' => 2,
                'qty' => 7,
            ],
        ],
    ]
*/

// ...Where the 'color' key is 'green', sum the 'price':

ray($fruit_multi)->where('color', 'green')->sum('price');

/*

    5.5

*/

// ...Where the 'color' key is not 'green', filter the items that have a 'price' greater than 2:

ray($fruit_multi)->whereNot('color', 'green')->filter(function($item, $key) {
    return $item['price'] > 2;
})->toArray();

/*

    [
        [
            'id' => 1,
            'name' => 'lemon',
            'color' => 'yellow',
            'price' => 2.25,
            'qty' => 2,
        ]
    ]

*/

// ...Where the 'color' key is 'green' or 'yellow', count the number of items:

ray($fruit_multi)->whereIn('color', ['green','yellow'])->count();

/*

    3

*/

// ...Retreive a column by the 'color' key and key the transformed array by the `name` key, sort by key:

ray($fruit_multi)->column('color', 'name')->sortByKeys()->toArray();

/*

    [
        'apple' => 'red',
        'lemon' => 'yellow',
        'lime' => 'green',
        'pear' => 'green',
    ]

*/
```

### Method Return Types

`Ray` methods will return different data types depedent on the desired outcome of the method. Each documented method definition indicates the data type returned.

- The `toArray()` method should be called at the end of the method chaining sequence to return the final transformed `array`:

```php
// toArray() returns the final transformed array:

ray($fruit_multi)->column('color', 'name')->sortByKeys()->toArray();

/*

    [
        'apple' => 'red',
        'lemon' => 'yellow',
        'lime' => 'green',
        'pear' => 'green',
    ]

*/
```

- Methods that return a `string` or an `integer` do not require the `toArray()` method. These methods should be called at the end of the method chaining sequence:

```php
// count() returns an integer:

ray($fruit_multi)->whereIn('color', ['green','yellow'])->count();

/*

    3

*/

// first() returns a string:

ray($fruit)->first();

/*

    'yellow'

*/
```

## Available Methods

The following methods are currently available:

- [sortByKeys](#sortbykeys)
- [sortByValues](#sortbyvalues)
- [has](#hasstring-key)
- [contains](#containsmixed-value--mixed-key)
- [sum](#sumstring-key)
- [avg](#avgstring-key)
- [count](#count)
- [values](#values)
- [first](#first)
- [last](#last)
- [except](#exceptarray-keys)
- [only](#onlyarray-keys)
- [unique](#uniquestring-key)
- [groupBy](#groupbystring-key)
- [column](#columnstring-key-string-key_by)
- [where](#wherestring-key-string-value)
- [whereIn](#whereinstring-key-array-values)
- [whereNot](#wherenotstring-key-string-value)
- [whereNotIn](#wherenotinstring-key-array-values)
- [filter](#filtercallable-callback)
- [reduce](#reducecallable-callback)

#### `sortByKeys()`
Sort the array by its keys.

```php
ray($fruit)->sortByKeys()->toArray();

/*

    Array
    (
        [apple] => red
        [lemon] => yellow
        [lime] => green
        [pear] => green
    )

*/
```

#### `sortByValues()`
Sort the array by its values.

```php
ray($fruit)->sortByValues()->toArray();

/*

    Array
    (
        [lime] => green
        [apple] => red
        [lemon] => yellow
    )

*/
```

#### `has(string $key)`
Determine if the array contains a given key.

```php
ray($fruit_multi)->has('price');

// true

ray($fruit_multi)->has('brand');

// false
```

#### `contains(string $value [, string $key])`
Determine if the array contains a given value.

```php
ray($fruit_multi)->contains('green');

// true

ray($fruit_multi)->contains('brown');

// false
```

Optionally provide a key to limit the `contains()` check

```php
ray($fruit_multi)->contains('color', 'green');

// true

ray($fruit_multi)->contains('color', 'brown');

// false
```

#### `sum(string $key)`
Get the sum of the values for the provided key.

```php
ray($fruit_multi)->sum('qty');

// 30

ray($fruit_multi)->sum('price');

// 8.74
```

#### `avg(string $key)`
Get the average of the values for the provided key.

```php
ray($fruit_multi)->avg('price');

// 2.185
```

#### `count()`
Get the count of the values.

```php
ray($fruit_multi)->count();

// 4
```

#### `values()`
Get the values of the array. Can be used to reindex the array with consecutive integers.

```php
ray($fruit)->values()->toArray();

/*

    Array
    (
        [0] => yellow
        [1] => red
        [2] => green
        [3] => green
    )

*/
```

#### `first()`
Get the first value of the array.

```php
ray($fruit)->first();

// yellow

ray($fruit_multi)->first();

/*

    Array
    (
        [id] => 1
        [name] => lemon
        [color] => yellow
        [price] => 2.25
        [qty] => 2
    )

*/
```

#### `last()`
Get the last value of the array.

```php
ray($fruit)->last();

// green

ray($fruit_multi)->last();

/*

    Array
    (
        [id] => 4
        [name] => pear
        [color] => green
        [price] => 2
        [qty] => 7
    )

*/
```

#### `except(array $keys)`
Get all array elements except for the provided keys.

```php
ray($fruit)->except(['apple', 'lime'])->toArray();

/*

    Array
    (
        [lemon] => yellow
        [pear] => green
    )

*/
```

#### `only(array $keys)`
Only get the array elements for the provided keys.

```php
ray($fruit)->only(['apple', 'lime'])->toArray();

/*

    Array
    (
        [apple] => red
        [lime] => green
    )

*/
```

#### `unique([string $key])`
Limit the array by unique value. Optionally limit by unique values of the provided key. The array keys are preserved. If there are duplicate values, the first key/value pair will be retained.

```php
ray($fruit)->unique()->toArray();

/*

    Array
    (
        [lemon] => yellow
        [apple] => red
        [lime] => green
    )

*/

ray($fruit_multi)->unique('color')->toArray();

/*

    Array
    (
        [0] => Array
            (
                [id] => 1
                [name] => lemon
                [color] => yellow
                [price] => 2.25
                [qty] => 2
            )
    
        [1] => Array
            (
                [id] => 2
                [name] => apple
                [color] => red
                [price] => 0.99
                [qty] => 12
            )
    
        [2] => Array
            (
                [id] => 3
                [name] => lime
                [color] => green
                [price] => 3.5
                [qty] => 9
            )
    
    )

*/
```

#### `groupBy(string $key)`
Group the array by a given key.

```php
ray($fruit_multi)->groupBy('color')->toArray();

/*

    Array
    (
        [yellow] => Array
            (
                [0] => Array
                    (
                        [id] => 1
                        [name] => lemon
                        [color] => yellow
                        [price] => 2.25
                        [qty] => 2
                    )
    
            )
    
        [red] => Array
            (
                [0] => Array
                    (
                        [id] => 2
                        [name] => apple
                        [color] => red
                        [price] => 0.99
                        [qty] => 12
                    )
    
            )
    
        [green] => Array
            (
                [0] => Array
                    (
                        [id] => 3
                        [name] => lime
                        [color] => green
                        [price] => 3.5
                        [qty] => 9
                    )
    
                [1] => Array
                    (
                        [id] => 4
                        [name] => pear
                        [color] => green
                        [price] => 2
                        [qty] => 7
                    )
    
            )
    
    )

*/
```

#### `column(string $key, [string $key_by])`
Retreive an entire column from the array. Optionally key the new transformed array by the provided key_by argument.

```php
ray($fruit_multi)->column('color')->toArray();

/*

    Array
    (
        [0] => yellow
        [1] => red
        [2] => green
        [3] => green
    )

*/

ray($fruit_multi)->column('color', 'name')->toArray();

/*

    Array
    (
        [lemon] => yellow
        [apple] => red
        [lime] => green
        [pear] => green
    )

*/
```

#### `where(string $key, string $value)`
Limit the array by a specific key and value.

```php
ray($fruit_multi)->where('color', 'green')->toArray();

/*

    Array
    (
        [2] => Array
            (
                [id] => 3
                [name] => lime
                [color] => green
                [price] => 3.5
                [qty] => 9
            )
    
        [3] => Array
            (
                [id] => 4
                [name] => pear
                [color] => green
                [price] => 2
                [qty] => 7
            )
    
    )

*/
```

#### `whereIn(string $key, array $values)`
Limit the array by a specific key and an array of values.

```php
ray($fruit_multi)->whereIn('color', ['green', 'yellow'])->toArray();

/*

    Array
    (
        [0] => Array
            (
                [id] => 1
                [name] => lemon
                [color] => yellow
                [price] => 2.25
                [qty] => 2
            )
    
        [2] => Array
            (
                [id] => 3
                [name] => lime
                [color] => green
                [price] => 3.5
                [qty] => 9
            )
    
        [3] => Array
            (
                [id] => 4
                [name] => pear
                [color] => green
                [price] => 2
                [qty] => 7
            )
    
    )

*/
```

#### `whereNot(string $key, string $value)`
Limit the array by a given key and value.

```php
ray($fruit_multi)->whereNot('color', 'green')->toArray();

/*

    Array
    (
        [0] => Array
            (
                [id] => 1
                [name] => lemon
                [color] => yellow
                [price] => 2.25
                [qty] => 2
            )
    
        [1] => Array
            (
                [id] => 2
                [name] => apple
                [color] => red
                [price] => 0.99
                [qty] => 12
            )
    
    )

*/
```

#### `whereNotIn(string $key, array $values)`
Limit the array by a specific key and an array of values.

```php
ray($fruit)->whereNotIn('color', ['green', 'yellow'])->toArray();

/*

    Array
    (
        [1] => Array
            (
                [id] => 2
                [name] => apple
                [color] => red
                [price] => 0.99
                [qty] => 12
            )
    
    )

*/
```

#### `filter(callable $callback)`
Filter the array by the provided callback.

```php
ray($fruit_multi)->filter(function($value, $key) {
    return $value['price'] > 3;
})->toArray();

/*

    Array
    (
        [2] => Array
            (
                [id] => 3
                [name] => lime
                [color] => green
                [price] => 3.5
                [qty] => 9
            )
    
)

*/

ray($fruit_multi)->filter(function($value, $key) {
    return $value['price'] < 3;
})->toArray();

/*

    Array
    (
        [0] => Array
            (
                [id] => 1
                [name] => lemon
                [color] => yellow
                [price] => 2.25
                [qty] => 2
            )
    
        [1] => Array
            (
                [id] => 2
                [name] => apple
                [color] => red
                [price] => 0.99
                [qty] => 12
            )
    
        [3] => Array
            (
                [id] => 4
                [name] => pear
                [color] => green
                [price] => 2
                [qty] => 7
            )
    
    )

*/
```

#### `reduce(callable $callback)`
Reduce the array by a callback to a single value.

```php
ray($fruit_multi)->reduce(function($carry, $value) {
    return $value['qty'] < 3 ? $carry + $value['qty'] : $carry;
});

// 2
```

## Contributing

Feel free to create a GitHub issue or send a pull request with any bug fixes. Please see the GutHub issue tracker for isses that require help.

## Acknowledgements

- [Colin Rafuse][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/tfhinc/ci-ray.svg?style=flat-square
[ico-php-version]: https://img.shields.io/packagist/php-v/tfhinc/ci-ray.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/tfhinc/ci-ray.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/tfhinc/ci-ray
[link-downloads]: https://packagist.org/packages/tfhinc/ci-ray
[link-author]: https://github.com/crafuse
[link-contributors]: ../../contributors
