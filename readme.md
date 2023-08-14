# Activity Log

[![The Whole Fruit Manifesto](https://img.shields.io/badge/writing%20standard-the%20whole%20fruit-brightgreen)](https://github.com/the-whole-fruit/manifesto)

This package adds a web interface that shows the activity log for projects that use [Backpack for Laravel](https://backpackforlaravel.com/). It relies on Spatie `laravel-activitylog` package, if you need further information on how to use it, head to https://spatie.be/docs/laravel-activitylog/.

How does it all work? Well:
- when a change happens to an Eloquent model, the Spatie package will make a note of it in the database;
- this package adds a web interface, so the admin can see the changes (aka activity log);

## Preview

![](https://user-images.githubusercontent.com/1032474/205863022-827f3248-a9f3-4d05-896f-5fa7a40227be.gif)

Don't belive how simple it is to use? Go ahead, try it right now, in [our online demo](https://demo.backpackforlaravel.com/admin/activity-log).  Edit some other entities, and check the [activity logs](https://demo.backpackforlaravel.com/admin/activity-log) page to see the changes.

## Installation

> Before official release, you need to add this to your `composer.json`'s `repositories` section before you can install it, because the package hasn't been submitted to Packagist yet. This step should be removed before official release.
>
        {
            "type": "vcs",
            "url": "git@github.com:Laravel-Backpack/activity-log.git"
        }

In your Laravel + Backpack project:

```bash
# install the package
composer require backpack/activity-log

# publish and run the migrations
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan migrate

# optional: publish the config file
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"

# optional: add a menu item for the Activity Logs page
php artisan backpack:add-menu-content "<x-backpack::menu-item title='Activity Logs' icon='la la-stream' :link=\"backpack_url('activity-log')\" />"
```

## Usage

Inside all the Models you want to be logged, add the usage of the `LogsActivity` Trait:

```diff
<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
+use Backpack\ActivityLog\Traits\LogsActivity;

class Article extends Model
{
    use CrudTrait;
+   use LogsActivity;
    ...
```

## Customization

TODO: questions to answer:
- What gets logged by default?
- How do you customize what gets logged?
- How do you customize the interface, if needed?

## Security

If you discover any security related issues, please email cristian.tabacitu@backpackforlaravel.com instead of using the issue tracker.

## Credits

- [Antonio Almeida](https://github.com/promatik)
- [Cristian Tabacitu](https://github.com/tabacitu)
- [All Contributors][link-contributors]

## License

This project was released under EULA, so you can install it on top of any Backpack & Laravel project. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/backpack/activity-log.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/backpack/activity-log.svg?style=flat-square
[link-author]: https://github.com/backpack
[link-contributors]: ../../contributors
