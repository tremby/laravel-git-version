<?php
namespace Tremby\LaravelGitVersion\Exception;

use RuntimeException;

class CouldNotGetTotalCommitsException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("Could not get the total of commits (no git project found)");
    }
}
