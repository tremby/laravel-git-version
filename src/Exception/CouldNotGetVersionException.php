<?php
namespace Tremby\LaravelGitVersion\Exception;

use RuntimeException;

class CouldNotGetVersionException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("Could not get version string (no version file and `git describe` failed)");
    }
}
