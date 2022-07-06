[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

# Create lists of options from different sources

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-options.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-options)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-options/run-tests?label=tests)](https://github.com/spatie/laravel-options/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-options/Check%20&%20fix%20styling?label=code%20style)](https://github.com/spatie/laravel-options/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-options.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-options)

A typical web application always has many select fields with options. This package makes it simple to
transform enums, models, states and arrays to a unified option structure.

Let's say you have the following enum:

```php
enum Hobbit: string
{
    case Frodo = 'frodo';
    case Sam = 'sam';
    case Merry = 'merry';
    case Pippin = 'pippin';
}
```

You can convert this to options like this:

```php
Options::forEnum(Hobbit::class)->toArray();
```

Which will return the following array:

```php
[
    ['label' => 'Frodo', 'value' => 'frodo'],
    ['label' => 'Sam', 'value' => 'sam'],
    ['label' => 'Merry', 'value' => 'merry'],
    ['label' => 'Pippin', 'value' => 'pippin'],
]
```

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/options.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/options)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can
support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.
You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards
on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-options
```

## Usage

You can create an `Options` object like this (we'll cover other things then enums later on):

```php
Options::forEnum(Hobbit::class);
```

You can get an array representation of the options like this:

```php
Options::forEnum(Hobbit::class)->toArray();
```

Or a JSON version like this:

```php
Options::forEnum(Hobbit::class)->toJson();
```

You can return options as part of a response in your controller, and they will be automatically converted into JSON:

```php
class ShowHobbitsController{
    public function __invoke(RingBearer $ringBearer){
        return [
            'ring_bearer' => $ringBearer,
            'hobbit_options' => Options::forEnum(Hobbit::class)
        ]   
    }
}
```

### Manipulating options

You can sort options by their label like this:

```php
Options::forEnum(Hobbit::class)->sort();
```

Or use a closure to sort the options:

```php
Options::forEnum(Hobbit::class)->sort(fn(Hobbit $hobbit) => $hobbit->value);
```

You can append additional data to the options like this:

```php
Options::forEnum(Hobbit::class)->append(fn(Hobbit $hobbit) => [
    'ring_bearer' => $hobbit === Hobbit::Frodo || $hobbit === Hobbit::Sam
]);
```

This will result in the following options array:

```php
[
    ['label' => 'Frodo', 'value' => 'frodo', 'ring_bearer' => true],
    ['label' => 'Sam', 'value' => 'sam', 'ring_bearer' => true],
    ['label' => 'Merry', 'value' => 'merry', 'ring_bearer' => false],
    ['label' => 'Pippin', 'value' => 'pippin', 'ring_bearer' => false],
]
```

You can filter the options that will be included:

```php
Options::forEnum(Hobbit::class)->filter(fn(Hobbit $hobbit) => $hobbit === Hobbit::Frodo);
```

Which will create a smaller options array:

```php
[
    ['label' => 'Frodo', 'value' => 'frodo'],
]
```

Or reject specific options to be included:

```php
Options::forEnum(Hobbit::class)->reject(fn(Hobbit $hobbit) => $hobbit === Hobbit::Frodo);
```

Which will create this options array:

```php
[
    ['label' => 'Sam', 'value' => 'sam'],
    ['label' => 'Merry', 'value' => 'merry'],
    ['label' => 'Pippin', 'value' => 'pippin'],
]
```

A unique `null` option can be added as such:

```php
Options::forEnum(Hobbit::class)->nullable();
```

This will add an option with the value `null`:

```php
[
    ['label' => '-', 'value' => null],
    ['label' => 'Frodo', 'value' => 'frodo'],
    ['label' => 'Sam', 'value' => 'sam'],
    ['label' => 'Merry', 'value' => 'merry'],
    ['label' => 'Pippin', 'value' => 'pippin'],
]
```

The label of the `null` option can be changed as such:

```php
Options::forEnum(Hobbit::class)->nullable('/');
```

### With enums

You can create options for native PHP enums, Spatie Enums and MyClabs Enums like this:

```php
Options::forEnum(Hobbit::class);
```

By default, the package will look for a static method called `labels` with the labels for the enum returned as an array:

```php
enum Hobbit: string
{
    case Frodo = 'frodo';
    case Sam = 'sam';
    case Merry = 'merry';
    case Pippin = 'pippin';
    
    public static function labels(): array
    {
       return [
           'frodo' => 'Frodo Baggins',
           'sam' => 'Sam Gamgee',
           'merry' => 'Merry Brandybuck',
           'pippin' => 'Pippin Took',
       ];
    }
}
```

Now the options array will look like this:

```php
[
    ['label' => 'Frodo Baggins', 'value' => 'frodo'],
    ['label' => 'Sam Gamgee', 'value' => 'sam'],
    ['label' => 'Merry Brandybuck', 'value' => 'merry'],
    ['label' => 'Pippin Took', 'value' => 'pippin'],
]
```

You can use another method name for the labels as such:

```php
Options::forEnum(Hobbit::class, 'hobbitLabels');
```

Or use a closure to resolve the label for the enum:

```php
Options::forEnum(Hobbit::class, fn(Hobbit $hobbit) => $hobbit->name. ' from the shire'));
```

### With models

You can create options for Laravel models like this:

```php
Options::forModels(Wizard::class);
```

Use a single model like this:

```php
Options::forModels(Wizard::first());
```

Or for a collection of models:

```php
Options::forModels(Wizard::all());
```

You can also pass a `Builder` instance:

```php
Options::forModels(Wizard::query()->where('name', 'gandalf'));
```

By default, the model's key (usually `id`) will be taken as a value and the `name` field as the label.

You can change the value field like this:

```php
Options::forModels(Wizard::class, value: 'uuid');
```

Or use a closure for determining the value:

```php
Options::forModels(Wizard::class, value: fn(Wizard $wizard) => $wizard->uuid());
```

You can change the label field as such:

```php
Options::forModels(Wizard::class, label: 'full_name');
```

Or you can use a closure to resolve the label:

```php
Options::forModels(Wizard::class, label: fn(Wizard $wizard) => $wizard->getName());
```

### With Select Options

If you're using options for a model in a lot of places, then each time, manually defining the label and/or value fields can become quite tedious:

```php
Options::forModels(
    Wizard::class, 
    label: fn(Wizard $wizard) => $wizard->getUuid(),
    value: fn(Wizard $wizard) => $wizard->getName(),
); // A lot of times within your code base
```

You can implement `Selectable` on a model (or any PHP class), which will automatically convert a model into an option with the correct fields:

```php
class Wizard extends Model implements Selectable
{
    public function toSelectOption(): SelectOption
    {
        return new SelectOption(
            $this->getName(),
            $this->getUuid()
        )
    }
}
```

Now you can omit the label and value field definitions:

```php
Options::forModels(Wizard::class);
```

You can also pass some extra data with the `SelectOption` like the `append` method we saw earlier:

```php
public function toSelectOption(): SelectOption
{
    return new SelectOption(
        $this->getName(),
        $this->getUuid(),
        ['color' => $this->color]
    )
}
```

Now the options array will look like this:

```php
[
    ['label' => 'Gandalf', 'id' => '...', 'color' => 'gray'],
    ['label' => 'Saruman', 'id' => '...', 'color' => 'white'],
]
```

As said earlier, implementing `Selectable` is not limited to models. You can implement it on any PHP class. In such a case, you can create options like this:

```php
Options::forSelectableOptions([
    SelectableOption::find(1),
    SelectableOption::find(2),
    SelectableOption::find(3),
]);
```

### With states

It is possible to create options from the Spatie model states package like this:

```php
Options::forStates(RingState::class);
```

You can pass in a model like this (otherwise, a temporary model is created):

```php
Options::forStates(RingState::class, $ring);
```

The package will automatically look for a `label` public method to use as a label:

```php
class LostRingState extends RingsState
{
    public function label(): string
    {
        return 'Lost';
    }
}
```

Or a `label` public property:

```php
class DestroyedRingState extends RingsState
{
    public string $label = 'destroyed';
}
```

You can change the name of the method or property which will be used as a label as such:

```php
Options::forStates(RingState::class, label: 'ringLabel');
```

Or use a closure to resolve the label for the state:

```php
Options::forStates(RingState::class, label: fn(RingState $state) => $state->label());
```

### With Arrays

You can create a set of options from an associative array:

```php
Options::forArray([
    'gondor' => 'Gondor',
    'rohan' => 'Rohan',
    'mordor' => 'Mordor',
])
```

You can also use a plain array as such:

```php
Options::forArray([
    'Gondor',
    'Rohan',
    'Mordor',
],useLabelsAsValue: true)
```

In this case, the labels and values will be equal.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/rubenvanassche/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ruben Van Assche](https://github.com/rubenvanassche)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
