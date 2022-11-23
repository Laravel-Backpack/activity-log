# Activity Log

[![The Whole Fruit Manifesto](https://img.shields.io/badge/writing%20standard-the%20whole%20fruit-brightgreen)](https://github.com/the-whole-fruit/manifesto)

This package adds an _activity log_ CRUD for projects that use [Backpack for Laravel](https://backpackforlaravel.com/) v5.  
This packages relies on Spatie `laravel-activitylog` package, if you need further information on how to use it, head to https://spatie.be/docs/laravel-activitylog/.

## Demo

Don't belive how simple it is to use? Go ahead, try it right now, in [our online demo](https://demo.backpackforlaravel.com/admin/activity-log).  
Edit some other entities, and check the [activity logs](https://demo.backpackforlaravel.com/admin/activity-log) page to see the changes.

## Installation

In your Laravel + Backpack project, install the package:

```bash
composer require backpack/activity-log
```

Publish and run the migrations:

```bash
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"

php artisan migrate
```

*Optional*, publish the config file:

```bash
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"
```

*Optional*, add a sidebar entry item for the Activity Logs page:

```bash
php artisan backpack:add-sidebar-content "<li class='nav-item'><a class='nav-link' href='{{ backpack_url('activity-log') }}'><i class='nav-icon la la-stream'></i> Activity Logs</a></li>"
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
