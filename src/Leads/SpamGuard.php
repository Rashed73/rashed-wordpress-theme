<?php
/**
 * Spam protection for the lead form, with no third-party service.
 *
 * - Honeypot: a field humans never see; bots fill it.
 * - Time token: signed timestamp issued with the form. Rejects instant
 *   submissions and stale or forged tokens. It is not tied to a session, so it
 *   keeps working when the page is served from a cache.
 * - Rate limit: a few submissions per visitor per hour. The visitor is a salted
 *   hash of the address; the address itself is never stored.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Leads;

final class SpamGuard
{
    public const HONEYPOT = 'rashid_website';
    public const TOKEN    = 'rashid_token';

    private const MIN_SECONDS = 3;
    private const MAX_SECONDS = 7 * DAY_IN_SECONDS;
    private const MAX_PER_HOUR = 5;

    /**
     * Token to place in the form when it is rendered.
     */
    public static function issueToken(): string
    {
        $time = (string) time();

        return $time . '.' . self::sign($time);
    }

    /**
     * Does the submission look like it came from a person using the form?
     *
     * @param array<string, mixed> $input Unslashed form data.
     */
    public static function looksHuman(array $input): bool
    {
        if (!empty($input[self::HONEYPOT])) {
            return false;
        }

        $token = isset($input[self::TOKEN]) && is_string($input[self::TOKEN]) ? $input[self::TOKEN] : '';
        [$time, $signature] = array_pad(explode('.', $token, 2), 2, '');

        if ($time === '' || !ctype_digit($time) || !hash_equals(self::sign($time), $signature)) {
            return false;
        }

        $age = time() - (int) $time;

        return $age >= self::MIN_SECONDS && $age <= self::MAX_SECONDS;
    }

    public static function isRateLimited(): bool
    {
        return (int) get_transient(self::bucket()) >= self::MAX_PER_HOUR;
    }

    /**
     * Count one accepted submission against the visitor.
     */
    public static function recordSubmission(): void
    {
        set_transient(self::bucket(), (int) get_transient(self::bucket()) + 1, HOUR_IN_SECONDS);
    }

    private static function sign(string $time): string
    {
        return hash_hmac('sha256', 'rashid-lead|' . $time, wp_salt('nonce'));
    }

    /**
     * Transient name for this visitor. Filterable for sites behind a proxy,
     * where the real address is in a header the site trusts.
     */
    private static function bucket(): string
    {
        $ip = (string) apply_filters('rashid_client_ip', $_SERVER['REMOTE_ADDR'] ?? '');

        return 'rashid_lead_' . substr(hash_hmac('sha256', $ip, wp_salt('auth')), 0, 32);
    }
}
