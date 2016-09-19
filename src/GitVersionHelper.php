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
    
    /**
     * Return commit hash with custom length
     *
     * @param null $length
     * @throws CouldNotGetCommitHashException if there is no commit hash and `git
     * rev-parse HEAD` fails
     * @return string
     *
     */
    public static function getHash($length=null)
    {
        // Remember current directory
        $dir = getcwd();

        // Change to base directory
        chdir(base_path());

        // Get version string from git
        $commit = shell_exec('git rev-parse HEAD');

        // Change back
        chdir($dir);

        if ($commit === null) {
            throw new Exception\CouldNotGetCommitHashException;
        }

        if($length) {
            $commit = substr($commit, 0, $length);
        }
        
        return $commit;
    }
}
