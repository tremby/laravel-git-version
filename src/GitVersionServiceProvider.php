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
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'git-version');
    }
}
