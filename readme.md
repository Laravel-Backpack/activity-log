# Activity Log

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![The Whole Fruit Manifesto](https://img.shields.io/badge/writing%20standard-the%20whole%20fruit-brightgreen)](https://github.com/the-whole-fruit/manifesto)

Ever wanted to see WHO changed WHAT and WHEN inside your app? Want to remember all the DB changes your users have done? Well... this package doesn't do that. [`spatie/laravel-activitylog`](https://github.com/spatie/laravel-activitylog) does, and does it very well. This package adds a web interface for it, for projects using [Backpack for Laravel](https://backpackforlaravel.com/). It gives your admin/superadmin the ability to see 
- the activities performed _by_ certain models;
- the activities performed _on_ certain models;
- and more;

How does it all work? Well:
- when a change happens to an Eloquent model, the Spatie package will make a note of it in the database;
- this package adds a web interface, so the admin can see the changes (aka activity log);

## Preview

![](https://user-images.githubusercontent.com/1032474/205863022-827f3248-a9f3-4d05-896f-5fa7a40227be.gif)

**NOTE**: The filters are a [Backpack\PRO](https://backpackforlaravel.com/products/pro-for-one-project) feature. If you don't have that package the filters wont be available.
## Demo

Try it right now, in [our online demo](https://demo.backpackforlaravel.com/admin/activity-log).  Edit some entities, and check the [activity logs](https://demo.backpackforlaravel.com/admin/activity-log).

## Installation

In your Laravel + Backpack project, install this package:

```bash
# install this interface package:
composer require backpack/activity-log

# add a menu item for it
php artisan backpack:add-menu-content "<x-backpack::menu-item title=\"Activity Logs\" icon=\"la la-stream\" :link=\"backpack_url('activity-log')\" />"
```
But also, if your package didn't already have [`spatie/laravel-activitylog`](https://github.com/spatie/laravel-activitylog) installed and set up, please [follow the installation steps in their docs](https://spatie.be/docs/laravel-activitylog/v4/installation-and-setup). We'll also copy-paste them here, for your convenience:
```bash
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan migrate
```

## Usage

> Note: If your models _are not_ configured to create activity logs yet, [read the FAQ on how to log all model events](#how-to-log-model-events-created-updated-deleted-etc). If you are unsure, then you have not configured them to log activities - read it.


### ActivityLog list view

Use it to browse all activity, filter the view, search the view, etc:

![Backpack ActivityLog list view](https://user-images.githubusercontent.com/1032474/264732691-b77f1585-08f0-4eb8-88aa-3b4226a65567.png)

### ActivityLog show view

Use it to see details about a particular activity:

![Backpack ActivityLog show view](https://user-images.githubusercontent.com/1032474/264734509-b13b520f-a732-4f84-be1c-db136e5fa160.png)

### CrudController operations

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

[link-packagist]: https://packagist.org/packages/backpack/activity-log
[link-downloads]: https://packagist.org/packages/backpack/activity-log
[link-author]: https://github.com/backpack
[link-contributors]: ../../contributors
