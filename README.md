
# Laravel Bones Macros (bones-macros) 

## Installation

To install bones-flash as a stand-alone module:

```sh
composer require "genealabs/bones-macros":"*"
```

or add it to you composer.json file:

```json
    "require": {
        /* ... */,
        "genealabs/bones-macros": "*"
    },
    /* ... */
```

And then add the service provider to your app.php config file:
```php
	// 'providers' => array(
		'GeneaLabs\BonesFlash\BonesMacrosServiceProvider',
    // );
```

## Usage

These HTML and FORM macros are intended to be used mainly in Blade tempaltes to reduce tedium.

## Methods

The following methods are available to use:

```php
// provides a Bootstrap-compatible cancel button that will take you to the previous page.
{{ Form::cancelButton() }}

// provides a select list with a range of intervals
{{ Form::selectRangeWithInterval($name, $start, $end, $interval, $default = null, $attributes = []) }}
```

## Dependencies

At this time this package requires:

- Laravel 4.2+
- Bootstrap 3.1+
