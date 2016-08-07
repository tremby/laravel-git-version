Laravel project git version getter
==================================

A helper class to get the current git version of the project.

Expects either a `version` file to exist in the `base_path()` of your project
containing a version string, or the `git` binary to be available.

Laravel version
---------------

This package works with both Laravel 4 and 5.

Installation
------------

Require it in your Laravel project:

    composer require tremby/laravel-git-version

Use
---

You can get the git version string with

    \Tremby\LaravelGitVersion\GitVersionHelper::getVersion()

Or you can get your app name and version number such as `my-project/1.0` with

    \Tremby\LaravelGitVersion\GitVersionHelper::getNameAndVersion()

The app's name is taken from `Config::get('app.name', 'app')`, so you can
configure it in your `config/app.php` file or leave it as the default of `app`.

A tiny feature to show the total of commits in your git repository

    \Tremby\LaravelGitVersion\GitVersionHelper::getTotalCommits()

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
