# Ray

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

Ray is an expressive PHP array library for the [Codeigniter](https://codeigniter.com/) framework.

## Installation

```bash
composer require tfhinc/ci-ray
```

- Copy the `tfhinc/ci-ray/src/Helpers/ray_helper.php` file to `application/helpers/ray_helper.php`

## Loading the Library

There are two available options for loading the `Ray` library:

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

- [sortByKeys](#sortByKeys())
- [sortByValues](#sortByValues())
- [contains](#contains())
- [has](#has())
- [sum](#sum())
- [avg](#avg())
- [count](#count())
- [first](#first())
- [last](#last())
- [keys](#keys())
- [values](#values())
- [except](#except())
- [only](#only())
- [unique](#unique())
- [groupBy](#groupBy())
- [column](#column())
- [where](#where())
- [whereIn](#whereIn())
- [whereNot](#whereNot())
- [whereNotIn](#whereNotIn())
- [filter](#filter())
- [reduce](#reduce())

#### sortByKeys()

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

#### sortByValues()

Sort the array by its values.

```php
ray($fruit)->sortByValues()->toArray();

/* Result

Array
(
    [lime] => green
    [apple] => red
    [lemon] => yellow
)

*/
```

#### has()

Determine if the array contains a given key.

```php
ray($fruit_multi)->has('price');

// Result
// true

ray($fruit_multi)->has('brand');

// Result
// false
```

#### contains()

Determine if the array contains a given value.

```php
ray($fruit_multi)->contains('green');

// Result
// true

ray($fruit_multi)->contains('brown');

// Result
// false
```

Optionally provide a key to limit the `contains()` check

```php
ray($fruit_multi)->contains('color', 'green');

// Result
// true

ray($fruit_multi)->contains('color', 'brown');

// Result
// false
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
