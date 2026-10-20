<?php
/**
 * PSR-4 style autoloader for the Rashid\ namespace.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid;

final class Autoloader
{
    private const PREFIX = 'Rashid\\';

    /**
     * Map the Rashid\ namespace onto a directory.
     */
    public static function register(string $baseDir): void
    {
        spl_autoload_register(static function (string $class) use ($baseDir): void {
            $file = self::fileFor($class, $baseDir);
            if ($file !== null) {
                require_once $file;
            }
        });
    }

    /**
     * Resolve a class name to its file, or null when it is not ours / missing.
     */
    private static function fileFor(string $class, string $baseDir): ?string
    {
        if (strncmp($class, self::PREFIX, strlen(self::PREFIX)) !== 0) {
            return null;
        }

        $relative = substr($class, strlen(self::PREFIX));
        $file     = $baseDir . '/' . str_replace('\\', '/', $relative) . '.php';

        return is_readable($file) ? $file : null;
    }
}
