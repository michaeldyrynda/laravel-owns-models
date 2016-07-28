# Model ownership for the Laravel PHP Framework
## v1.0.0

![Travis Build Status](https://travis-ci.org/michaeldyrynda/laravel-owns-models.svg?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/michaeldyrynda/laravel-owns-models/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/michaeldyrynda/laravel-owns-models/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/michaeldyrynda/laravel-owns-models/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/michaeldyrynda/laravel-owns-models/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/iatstuti/laravel-owns-models/v/stable)](https://packagist.org/packages/iatstuti/laravel-owns-models)
[![Total Downloads](https://poser.pugx.org/iatstuti/laravel-owns-models/downloads)](https://packagist.org/packages/iatstuti/laravel-owns-models)
[![License](https://poser.pugx.org/iatstuti/laravel-owns-models/license)](https://packagist.org/packages/iatstuti/laravel-owns-models)

This is a small trait that allows you to determine whether the using model owns some other model within your application. Using the trait makes it trivial to perform authorisation checks to determine whether some user has access to another model, for example.

# Installation

This trait is installed via [Composer](http://getcomposer.org/). To install, simply add it to your `composer.json` file:

```
{
    "require": {
        "iatstuti/laravel-owns-models": "~1.0"
    }
}
```

Then run composer to update your dependencies:

```
$ composer update
```

In order to use this trait, import it in your Eloquent model. You can then use either the `owns` or `doesntOwn` method to determine if some model owns another, based on either the default or explicit keys for each.

```php
<?php

use Iatstuti\Database\Support\OwnsModels;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use OwnsModels;
}

class Post extends Model
{
}

$user = User::find($user_id);
$post = Post::find($post_id);

if ($user->owns($post)) {
    // Continue execution
}

if ($user->doesntOwn($post)) {
    // Generate some authorisation error
}
```

Prior to Laravel 5.2, the primary key of the model was returned as a string. As a result, this package does loose comparisons by default, to provide maximum version compatibility.

If you want to perform strict comparisons in older versions, you can add your primary key field to the `$casts` property as an integer and call the `owns` method with additional parameters:

```php
$user->owns($post, null, true);
$user->doesntOwn($post, null, true);
```

At the first release of this package, I believe people are more likely to change the second (`$foreignKey`) parameter than explicitly set the third (`$strict`) parameter. If you think otherwise, or have a better way of handling this, please get in touch!

# Support

If you are having general issues with this package, feel free to contact me on [Twitter](https://twitter.com/michaeldyrynda).

If you believe you have found an issue, please report it using the [GitHub issue tracker](https://github.com/michaeldyrynda/laravel-owns-models/issues), or better yet, fork the repository and submit a pull request.

If you're using this package, I'd love to hear your thoughts. Thanks!
