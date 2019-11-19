Laravel/Lumen project git version getter
========================================

A helper class to get the current git version of the project.

Expects either a `version` file to exist in the `base_path()` of your project
containing a version string, or the `git` binary to be available.

Framework version
-----------------

This package works with both Laravel 4 and 5, and also Lumen.

Installation
------------

Require it in your Laravel/Lumen project:

    composer require tremby/laravel-git-version

### Optional packages

This module uses [Symfony's Process component][process] if available,
or falls back to `shell_exec` otherwise.
So if your deployment environment has `shell_exec` disabled,
you can work around this by installing `symfony/process`.

[process]: https://github.com/symfony/process

Use
---

You can get the git version string with

    \Tremby\LaravelGitVersion\GitVersionHelper::getVersion()

Or you can get your app name and version number such as `my-project/1.0` with

    \Tremby\LaravelGitVersion\GitVersionHelper::getNameAndVersion()

The app's name is taken from `Config::get('app.name', 'app')`, so you can
configure it in your `config/app.php` file or leave it as the default of `app`.

### Recommended usage pattern

Ensure your git tags are pushed to your servers
so that the versions are described properly.

During development and possibly in staging environments
allow the version to be determined automatically
(this is done via `git describe`).

As part of your production deployment procedure,
write a `version` file (perhaps via a command like
`git describe --always --tags --dirty >version`,
since this is the command this package would run otherwise).
When this `version` file exists the package will use its contents
rather than executing `git`, saving some processor and IO time.

Add `/version` to your `.gitignore` file
so your working tree stays clean and you don't accidentally commit it.

View
----

A view is provided which just outputs an HTML comment with the return value of
`getNameAndVersion()`. I like to include this in the main layout template of the
project.

To use this, install the service provider by adding it to your `config/app.php`
file:

    'providers' => [
        ...
        Tremby\LaravelGitVersion\GitVersionServiceProvider::class,
    ],

Then the view is available:

    @include('git-version::version-comment')
