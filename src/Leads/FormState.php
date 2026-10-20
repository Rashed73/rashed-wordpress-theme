<?php
/**
 * Hands the outcome of a submission from the handler to the form that renders.
 *
 * The handler runs before the template, so it records errors and the values to
 * refill; the form reads them. A successful submission redirects with a query
 * argument instead, which also stops a reload from sending the form twice.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Leads;

final class FormState
{
    public const SENT_ARG = 'rashid_sent';

    /** @var array<string, string> */
    private static array $errors = [];

    /** @var array<string, string> */
    private static array $values = [];

    private static string $notice = '';

    private static string $token = '';

    /**
     * @param array<string, string> $errors
     * @param array<string, string> $values
     */
    public static function fail(array $errors, array $values, string $notice = ''): void
    {
        self::$errors = $errors;
        self::$values = $values;
        self::$notice = $notice;
    }

    /**
     * Keep the visitor's original time token across a failed attempt, so fixing a
     * typo and sending again is not mistaken for an instant bot submission.
     */
    public static function keepToken(string $token): void
    {
        self::$token = $token;
    }

    public static function token(): string
    {
        return self::$token;
    }

    public static function error(string $field): string
    {
        return self::$errors[$field] ?? '';
    }

    public static function notice(): string
    {
        return self::$notice;
    }

    public static function value(string $field): string
    {
        return self::$values[$field] ?? '';
    }

    /**
     * Was the form with this source just sent successfully?
     */
    public static function wasSent(string $source): bool
    {
        return isset($_GET[self::SENT_ARG]) && sanitize_key(wp_unslash($_GET[self::SENT_ARG])) === $source;
    }
}
