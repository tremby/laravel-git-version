<?php
namespace Tremby\LaravelGitVersion;

use Config;
use Symfony\Component\Process\Process;

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
     * @throws Exception\CouldNotGetVersionException if there is no version file and `git
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
        $process = new Process(['git describe', '--always', '--tags', '--dirty']);
        $process->run();
        $output = $process->getOutput();

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
     * @throws Exception\CouldNotGetVersionException if there is no version file and `git
     * describe` fails
     * @return string App name and version string
     */
    public static function getNameAndVersion()
    {
        return self::appName() . '/' . self::getVersion();
    }
}
