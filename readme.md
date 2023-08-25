# Activity Log

[![The Whole Fruit Manifesto](https://img.shields.io/badge/writing%20standard-the%20whole%20fruit-brightgreen)](https://github.com/the-whole-fruit/manifesto)

This package introduces a web interface designed to display the activity log for projects utilizing [Backpack for Laravel](https://backpackforlaravel.com/). It is built upon the Spatie laravel-activitylog package. For more detailed guidance on how to implement it, refer to the official documentation at https://spatie.be/docs/laravel-activitylog/.

How does it all work? Well:
- when a change happens to an Eloquent model, the Spatie package will make a note of it in the database;
- this package adds a web interface, so the admin can see the changes (aka activity log);

## Preview

![](https://user-images.githubusercontent.com/1032474/205863022-827f3248-a9f3-4d05-896f-5fa7a40227be.gif)

Don't belive how simple it is to use? Go ahead, try it right now, in [our online demo](https://demo.backpackforlaravel.com/admin/activity-log).  Edit some other entities, and check the [activity logs](https://demo.backpackforlaravel.com/admin/activity-log) page to see the changes.

## Installation

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
php artisan backpack:add-menu-content "<x-backpack::menu-item title=\"Activity Logs\" icon=\"la la-stream\" :link=\"backpack_url('activity-log')\" />"
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

---

If you intend to display the activity log shortcut button on CRUD List, enable each button using the following approach:

```diff
<?php

namespace App\Http\Controllers\Admin;

...

class ArticleCrudController extends CrudController
{
    ...
+    use \Backpack\ActivityLog\Http\Controllers\Operations\ShowModelActivityLogsOperation;
+    use \Backpack\ActivityLog\Http\Controllers\Operations\ShowEntryActivityLogsOperation;
    ...
```

**ShowModelActivityLogsOperation**: Displays a button at the Model level, allowing users to access a comprehensive list of all Model activities.  
**ShowEntryActivityLogsOperation**: Adds line buttons to each entry in the list, enabling users to view activity logs specific to individual entries.

---

This package also offers shortcuts for Causers, entities that initiate activities on other entities. While typically users, other entities can trigger changes too.  
To enable the activity log shortcut button on causers:

```diff
<?php

namespace App\Http\Controllers\Admin;

...

class ExampleCrudController extends CrudController
{
    ...
+    use \Backpack\ActivityLog\Http\Controllers\Operations\ShowCauserModelActivityLogsOperation;
+    use \Backpack\ActivityLog\Http\Controllers\Operations\ShowCauserEntryActivityLogsOperation;
    ...
```

---

**Quick note**: If you haven't customized your `UserCrudController` yet, you can achieve this by binding the backpack controller to your custom controller.

`App\Providers\AppServiceProvider.php`
```php
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \Backpack\PermissionManager\app\Http\Controllers\UserCrudController::class,
            \App\Http\Controllers\Admin\UserCrudController::class
        );
```

`App\Http\Controllers\Admin\UserCrudController.php`
```php
use Backpack\PermissionManager\app\Http\Controllers\UserCrudController as OriginalUserCrudController;

class UserCrudController extends OriginalUserCrudController
{
    use \Backpack\ActivityLog\Http\Controllers\Operations\ShowCauserModelActivityLogsOperation;
    use \Backpack\ActivityLog\Http\Controllers\Operations\ShowCauserEntryActivityLogsOperation;
}
```

## FAQ

#### What gets logged by default?

By default, the backpack activity logger records changes to the **fillable fields** that have been **modified**.

#### How do you customize what gets logged?

To customize the logged information, you can override the `getActivitylogOptions` method in your models.

```php
public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->logFillable()
        ->logOnlyDirty();
}
```

#### Can I customize the logs even further?

Yes, you can! The Spatie laravel-activitylog package offers a range of advanced customization options.
For detailed insights into these options, check out the official Log Options documentation by Spatie, at https://spatie.be/docs/laravel-activitylog/v4/api/log-options

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
