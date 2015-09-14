<?php
namespace Tremby\LaravelGitVersion;

use Illuminate\Support\ServiceProvider;

class GitVersionServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        if (method_exists($this, 'loadViewsFrom')) {
            // Laravel 5
            $this->loadViewsFrom(__DIR__ . '/../resources/views', 'git-version');
        } else {
            // Laravel 4
            $this->package('tremby/git-version', null, __DIR__ . '/../resources');
        }
    }
}
