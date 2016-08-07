<?php
namespace Tremby\LaravelGitVersion;

use Config;

class GitVersionHelper
{
    private static function versionFile()
    {
        return base_path() . '/version';
    }

    private static function appName()
    {
        return Config::get('app.name', 'app');
    }

    /**
     * Get the app's version string
     *
     * If a file <base>/version exists, its contents are trimmed and used.
     * Otherwise we get a suitable string from `git describe`.
     *
     * @throws CouldNotGetVersionException if there is no version file and `git
     * describe` fails
     * @return string Version string
     */
    public static function getVersion()
    {
        // If we have a version file, just return its contents
        if (file_exists(self::versionFile())) {
            return trim(file_get_contents(self::versionFile()));
        }

        // Remember current directory
        $dir = getcwd();

        // Change to base directory
        chdir(base_path());

        // Get version string from git
        $output = shell_exec('git describe --always --tags --dirty');

        // Change back
        chdir($dir);

        if ($output === null) {
            throw new Exception\CouldNotGetVersionException;
        }

        return trim($output);
    }

    /**
     * Get total git commits
     *
     * @throws CouldNotGetTotalCommitsException if there is no git project
     * @return string Version string
     */
    public static function getTotalCommits()
    {
        // Remember current directory
        $dir = getcwd();

        // Change to base directory
        chdir(base_path());

        // Get version string from git
        $output = shell_exec('git rev-list HEAD --count');

        // Change back
        chdir($dir);

        if ($output === null) {
            throw new Exception\CouldNotGetTotalCommitsException;
        }

        return trim($output);
    }

    /**
     * Get a string identifying the app and version
     *
     * @see getVersion
     * @throws CouldNotGetVersionException if there is no version file and `git
     * describe` fails
     * @return string App name and version string
     */
    public static function getNameAndVersion()
    {
        return self::appName() . '/' . self::getVersion();
    }
}
