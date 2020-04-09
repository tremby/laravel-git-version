<?php
namespace Tremby\LaravelGitVersion;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\RuntimeException;

class GitVersionHelper
{
    private static function versionFile()
    {
        return base_path() . '/version';
    }

    private static function appName()
    {
        return config('app.name', 'app');
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

        $path = base_path();

        // Get version string from git
        $command = 'git describe --always --tags --dirty';
        $fail = false;
        if (class_exists('\Symfony\Component\Process\Process')) {
            try {
                if (method_exists(Process::class, 'fromShellCommandline')) {
                    $process = Process::fromShellCommandline($command, $path);
                } else {
                    $process = new Process($command, $path);
                }
            
                $process->mustRun();
                $output = $process->getOutput();
            } catch (RuntimeException $e) {
                $fail = true;
            }
        } else {
            // Remember current directory
            $dir = getcwd();

            // Change to base directory
            chdir($path);

            $output = shell_exec($command);

            // Change back
            chdir($dir);

            $fail = $output === null;
        }

        if ($fail) {
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
