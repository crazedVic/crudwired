# Skele - Unopinionated

Rapid Laravel app development package.

Heavily inspired by and mostly a repurposed version of [redbastie/skele](https://github.com/redbastie/skele)
Kevin created and then discontinued work on this repo all within the span of a few days.
It has some great features but was still too opinionated for me and therefore difficult to add to an existing project.
I have reworked the commands and the corresponding code to not remove most of the features but introduce geater flexibility.

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
- honey (removed)
- PWA support (removed)

## Installation - New Project

Create a new Laravel 8 project:

    laravel new my-app

Configure your `.env` app, database, and mail values:

    APP_*
    DB_*
    MAIL_*

Require Skele via composer:

    composer require redbastie/skele

Install Skele:

    php artisan skele:install --force
    comment out the default route in routes/web.php

Install Tailwind:
    
    php artisan skele:tailwind
    
Install Auth scaffolding:

    php artisan skele:auth


## Installation - Existing Project

If your project is not yet using [TailwindCSS](https://tailwindcss.com/docs/guides/laravel), you will need to either install it yourself, or run the following command:
    
    php artisan skele:tailwind [--force]
    
If your project is already using  [Laravel Liveware](https://laravel-livewire.com/docs/2.x/installation), the Artisan commands will generate Livewire components and views based off the livewire configuration, either the default one included in the vendor/livewire/config or the one published to config/livewire.php

This package depends on some javascript code and of curse the presence of a view/layouts/app.blade.php which is setup to support Livewire.  By default the install command will not overwrite any files unless you specify --force, which should be used with caution.  The install command also creates a sample Index livewire component if one does not yet exist.

    php artisan skele:install  [--force]

At this point you can now use the remaining artisan commands to build out models, components, crud and lists in your existing project.  Details below.

## Commands

### Install

    php artisan skele:install [--force]

Installs the base index component, config files, JS assets, index & layout/app views.

### Auth

    php artisan skele:auth  [--force]

Generates user model & factory, auth scaffolding components & views for login, logout, password forgot & reset, register, and home.

### Model

    php artisan skele:model {class}  [--force]

Generates a new model & factory with automatic migration methods included.

#### Examples

    php artisan skele:model Vehicle
    php artisan skele:model Admin/Vehicle  

### Migrate

    php artisan skele:migrate {--fresh} {--seed}

Runs the automatic migrations via the `migration` methods in your models. This uses doctrine in order to diff & apply the necessary changes to your database. Traditional Laravel migration files will be run before automatic migration methods. Optionally use `--fresh` to wipe the database before, and `--seed` to run seeders after.

#### Examples

    php artisan skele:migrate
    php artisan skele:migrate --fresh
    php artisan skele:migrate --fresh --seed

### Component

    php artisan skele:component {class} {--full} {--modal}

Generates a new component & view file. Optionally use the `--full` option to generate a full-page component with automatic routing properties included, or `--modal` to generate a modal component.

#### Examples

    php artisan skele:component Partial
    php artisan skele:component Contact --full
    php artisan skele:component Alert --modal

### CRUD

    php artisan skele:crud {class}  [--force]

Generates CRUD components & views for a specified model class. If the model does not currently exist, it will be created automatically.

#### Examples

    php artisan skele:crud Vehicle
    php artisan skele:crud Admin/Vehicle

### List

    php artisan skele:list {class} {--model=}  [--force]

Generates a list component with searching & infinite scrolling for the specified model. A `--model` must be specified.

#### Examples

    php artisan skele:list Vehicles --model=Vehicle
    php artisan skele:list Admin/Vehicles --model=Admin/Vehicle
