# Activity Log

[![The Whole Fruit Manifesto](https://img.shields.io/badge/writing%20standard-the%20whole%20fruit-brightgreen)](https://github.com/the-whole-fruit/manifesto)

Ever wanted to see WHO changed WHAT inside your app? To remember all the DB changes your users have done? Then you're probably using [`spatie/laravel-activitylog`](https://github.com/spatie/laravel-activitylog). This package is a web interface for projects who use it. It gives your admin/superadmin the ability to see 
- the activities performed _by_ certain models;
- the activities performed _on_ certain models;
- and more;

How does it all work? Well:
- when a change happens to an Eloquent model, the Spatie package will make a note of it in the database;
- this package adds a web interface, so the admin can see the changes (aka activity log);

## Preview

![](https://user-images.githubusercontent.com/1032474/205863022-827f3248-a9f3-4d05-896f-5fa7a40227be.gif)

Don't belive how simple it is to use? Go ahead, try it right now, in [our online demo](https://demo.backpackforlaravel.com/admin/activity-log).  Edit some other entities, and check the [activity logs](https://demo.backpackforlaravel.com/admin/activity-log) page to see the changes.

## Installation

In your Laravel + Backpack project:

```bash
# install this interface package:
composer require backpack/activity-log

# add a menu item for it
php artisan backpack:add-menu-content "<x-backpack::menu-item title=\"Activity Logs\" icon=\"la la-stream\" :link=\"backpack_url('activity-log')\" />"


# --------------------------------------------------------
# IMPORTANT - Finish Installing spatie/laravel-activitylog
# --------------------------------------------------------
# If you haven't finished their installation process, before installing this interface, do it now.
# You can follow the docs in https://spatie.be/docs/laravel-activitylog/v4/installation-and-setup
# But we'll copy-paste the instructions here too, for your convenience:
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan migrate
# --------------------------------------------------------
```

## Usage

Your existing activities should show up right away, no extra configuration needed. If your models create activity logs, click the menu item and you'll see them in the interface. 

> If you haven't yet configured your models to create activity logs, read the FAQ below. We'll show you how to easily log all model events.

If you want your CrudControllers to show links to their activities, just use one or both of the provided Backpack operations:
- **ModelActivityOperation**: Displays a general button, allowing users to see a comprehensive list of all activities for that Model.  
- **EntryActivityOperation**: Adds line buttons to each entry in the list, enabling users to view activity logs specific to individual entries.

#### ModelActivityOperation - on a CrudController, display a link to that Model's activity log

Say you have a `UserCrudController`. If you want a new button to show up next to _the `Add User` button_, that will take you to _all of the activities of all Users_, then use `\Backpack\ActivityLog\Http\Controllers\Operations\ModelActivityOperation` on your `UserCrudController`.

```diff
<?php

namespace App\Http\Controllers\Admin;

...

class ArticleCrudController extends CrudController
{
    ...
+    use \Backpack\ActivityLog\Http\Controllers\Operations\ModelActivityOperation;
    ...
```

#### EntryActivityOperation - on a CrudController, display a link to each entry's activity log

Say you have a `UserCrudController`. If you want a new button to show up next _each entry_, that will take you to all of the activities of _that entry_, then use `\Backpack\ActivityLog\Http\Controllers\Operations\EntryActivityOperation` on your `UserCrudController`.

```diff
<?php

namespace App\Http\Controllers\Admin;

...

class ArticleCrudController extends CrudController
{
    ...
+    use \Backpack\ActivityLog\Http\Controllers\Operations\EntryActivityOperation;
    ...
```

## FAQ

#### What gets logged by default?

By default, the nothing gets logged. Please configure `spatie/laravel-activitylog` to do the logging according to your needs. See the question below for the most common use case.

#### How to log model events (`created`, `updated`, `deleted` etc)

> Note: this is NOT a feature that is provided by this package. It's provided by `spatie/laravel-activitylog`.
> But we try to help document the most common use case we have found, so it's easier for you to do it.

You want a new Activity registered, whenever a model is `created`, `updated`, `deleted` etc? So that there's a record of WHO did WHAT and WHEN it happened? Here's how you can set up `spatie/laravel-activitylog` to log all model events.

**Step 1.** Create a new model trait at `App\Models\Traits\LogsActivity.php` with the following content:

```php
<?php

namespace App\Models\Traits;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity as OriginalLogsActivity;

trait LogsActivity
{
    use OriginalLogsActivity;

    /**
     * Spatie Log Options
     * By default will log only the changes between fillables
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }
}
```

**Step 2.** Use that trait on all Models where you want all events logged:

```diff
<?php

namespace App\Models;

+use App\Models\Traits\LogsActivity;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Article extends Model
{
    use CrudTrait;
+   use LogsActivity;
    ...
```

Notice that this trait extends the default `Spatie\Activitylog\Traits\LogsActivity` and defines the `getActivitylogOptions()` method providing some reasonable defaults. If you want to customize, see  [details here](https://spatie.be/docs/laravel-activitylog/v4/advanced-usage/logging-model-events) and [options here](https://spatie.be/docs/laravel-activitylog/v4/api/log-options).
  

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

#### I am using `backpack/permission-manager` so I don't have access to `UserCrudController`

If you haven't customized your `UserCrudController` yet, you can achieve this by binding the Backpack controller to your custom controller. In your `App\Providers\AppServiceProvider.php`:

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

Then in your `App\Http\Controllers\Admin\UserCrudController.php` you can do something like:

```php
use Backpack\PermissionManager\app\Http\Controllers\UserCrudController as OriginalUserCrudController;

class UserCrudController extends OriginalUserCrudController
{
    use \Backpack\ActivityLog\Http\Controllers\Operations\ModelActivityOperation;
    use \Backpack\ActivityLog\Http\Controllers\Operations\EntryActivityOperation;
}
```


## Security

If you discover any security related issues, please email cristian.tabacitu@backpackforlaravel.com instead of using the issue tracker.

## Credits

- [Antonio Almeida](https://github.com/promatik)
- [Cristian Tabacitu](https://github.com/tabacitu)
- [All Contributors][link-contributors]

## License

This project was released under MIT License, so you can install it on top of any Backpack & Laravel project. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/backpack/activity-log.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/backpack/activity-log.svg?style=flat-square
[link-author]: https://github.com/backpack
[link-contributors]: ../../contributors
