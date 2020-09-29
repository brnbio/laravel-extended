# laravel-extended
Missing features for laravel
 

#### 1. make:view

Generates a view using the view stub.

```text
Description:
  Create a new resource view

Usage:
  make:view [options] [--] <name>

Arguments:
  name                  The name of the class

Options:
      --force           Create the class even if the model already exists
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
      --env[=ENV]       The environment the command should run under
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```

```php
php artisan make:view users.index
```
```blade
Result: /resources/views/users/index.blade.php

@extends('layouts/app')
@section('content')

@endsection
```

If you want to use a custom stub, create a new view.stub file in /stubs in your root directory.

#### 2. make:route

```text
Description:
  Create a new route

Usage:
  make:route [options] [--] <link> <controller> [<action>]

Arguments:
  link                  The url of this route
  controller            Controller class of the route
  action                Name of the function if the class is not invokable

Options:
  -p, --post            Generate a post route
      --name[=NAME]     Name of the route [default: false]
      --file[=FILE]     Name of the route file [default: "web"]
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
      --env[=ENV]       The environment the command should run under
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
```php
// php artisan make:route users Users/IndexController
// Result: routes/web.php

...
Route::get('/users', Controllers\Users\IndexController:class);

```
```php
// php artisan make:route users/create Users/CreateController store -p --name users.store
// Result: routes/web.php

...
Route::post('/users/create', [Controllers\Users\CreateController:class, 'store'])->name('users.store');

```

Make sure, that you add App\Http\Controllers as usage in your routes file.

```php
// file: routes/web.php

use App\Http\Controllers;

...
```