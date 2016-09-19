<?php
namespace Tremby\LaravelGitVersion\Exception;

use RuntimeException;

class CouldNotGetCommitHashException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("Could not get Commit hash (no commit file and `git describe` failed)");
    }
}
