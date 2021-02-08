# crazed/crudwired

Rapid Laravel app development package.

Heavily inspired by and mostly a repurposed version of [redbastie/skele](https://github.com/redbastie/skele).  Kevin created and then discontinued work on this repo all within the span of a few days, instead focusing his efforts on [redbastie/tailwire](https://github.com/redbastie/tailwire), so be sure to check that out!

In terms of skele, it had some great features but in my opinion (har har) was still too opinionated and above all pretty much impossible to integrate into an existing project.  I have therefore reworked most of the commands without removing most of the features, yet introducing greater flexibility.

#### Requirements

- Laravel 8
- PHP 7.4
- NPM

#### Packages Used

- [Laravel Timezone](https://github.com/jamesmills/laravel-timezone)
- [Doctrine DBAL](https://github.com/doctrine/dbal)
- [Blade Heroicons](https://github.com/blade-ui-kit/blade-heroicons)
- [Laravel Liveware](https://laravel-livewire.com/docs/2.x/installation)

#### Features

- Rapid scaffolding commands (auth, components, CRUD, models)
- Automatic routing, migrations, timezones, & password hashing
- Bare-bones blade views, ready for you to customize
- Infinite scrolling & modal toggle support
- ~~honey~~ 
- ~~PWA support~~

## Installation - New Project

Create a new Laravel 8 project:

    laravel new my-app

Configure your `.env` app, database, and mail values:

    APP_*
    DB_*
    MAIL_*

Require Livewired via composer:

    composer require crazed/crudwired

Install Livewired:

    php artisan crudwired:install --force
    
Update routes/web.php, comment out the default route in routes/web.php:

    // Route::get('/', function () {
    //     return view('welcome');
    // });

Install Tailwind:
    
    php artisan crudwired:tailwind
    
Install Auth scaffolding (optional):

    php artisan crudwired:auth --force


## Installation - Existing Project

Require Livewired via composer:

    composer require crazed/crudwired

If your project is not yet using [TailwindCSS](https://tailwindcss.com/docs/guides/laravel), you will need to either install it yourself, or run the following command:
    
    php artisan crudwired:tailwind [--force]
    
If your project is already using  [Laravel Liveware](https://laravel-livewire.com/docs/2.x/installation), the Artisan commands will generate Livewire components and views based off the livewire configuration, either the default one included in the vendor/livewire/config or the one published to config/livewire.php

This package depends on some javascript code and of curse the presence of a view/layouts/app.blade.php which is setup to support Livewire.  By default the install command will not overwrite any files unless you specify --force, which should be used with caution.  The install command also creates a sample Index livewire component if one does not yet exist.

    php artisan crudwired:install  [--force]

At this point you can now use the remaining artisan commands to build out models, components, crud and lists in your existing project.  Details below.

## Commands

### Install

    php artisan crudwired:install [--force]

Installs the base index component, config files, JS assets, index & layout/app views.  **The crudwired.js is copied into the resources/js folder, and then the app.js file is modified to append an import of the crudwired.js.  This .js file is required for infinite scrolling support.**

### Auth

    php artisan crudwired:auth  [--force]

Generates user model & factory, auth scaffolding components & views for login, logout, password forgot & reset, register, and home.  **No longer supports Honey, Honey was creating additional tables during migration and there appears to be now way to avoid this, so removed. User model changes are now handled by Auth instead of Install.**

### Tailwind

    php artisan crudwired:tailwind [--force]

Generates the tailwind.config.js and the webpack.mix.js, updates the resources/css/app.css to include the @tailwind libraries.  Then uses npm to install all the necessary libraries, and then npm run dev to compile everything.

### Model

    php artisan crudwired:model {class}  [--force]

Generates a new model & factory with automatic migration methods included.

#### Examples

    php artisan crudwired:model Vehicle
    php artisan crudwired:model Admin/Vehicle  

### Migrate

    php artisan crudwired:migrate {--fresh} {--seed}

Runs the automatic migrations via the `migration` methods in your models. This uses doctrine in order to diff & apply the necessary changes to your database. Traditional Laravel migration files will be run before automatic migration methods. Optionally use `--fresh` to wipe the database before, and `--seed` to run seeders after.

#### Examples

    php artisan crudwired:migrate
    php artisan crudwired:migrate --fresh
    php artisan crudwired:migrate --fresh --seed

### Component

    php artisan crudwired:component {class} {--full} {--modal}

Generates a new component & view file. Optionally use the `--full` option to generate a full-page component with automatic routing properties included, or `--modal` to generate a modal component.  **Components are generated into folders grouped by type; modal, partial, full.**

#### Examples

    php artisan crudwired:component Partial
    php artisan crudwired:component Contact --full
    php artisan crudwired:component Alert --modal

### CRUD

    php artisan crudwired:crud {class}  [--force] [--auth]

Generates CRUD components & views for a specified model class. If the model does not currently exist, it will be created automatically.  **By default the Index livewire component has the middleware = 'auth' disabled, use --auth to prevent anon access to the generated CRUD views.**

#### Examples

    php artisan crudwired:crud Vehicle
    php artisan crudwired:crud Admin/Vehicle

### List

    php artisan crudwired:list {class} {--model=}  [--force]

Generates a list component with searching & infinite scrolling for the specified model. A `--model` must be specified.

#### Examples

    php artisan crudwired:list Vehicles --model=Vehicle
    php artisan crudwired:list Admin/Vehicles --model=Admin/Vehicle
