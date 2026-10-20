<?php
/**
 * Receives a lead form submission and turns it into a stored, announced lead.
 *
 * The form posts back to its own page, so on a mistake the same page renders again
 * with the errors and the visitor's text kept. Success redirects (post/redirect/get)
 * so a reload cannot send the form twice.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Leads;

use Rashid\Module;

final class SubmissionHandler implements Module
{
    /** Hidden field that marks a post as coming from the lead form. */
    public const MARKER = 'rashid_lead_form';
    public const SOURCE = 'rashid_source';

    public function register(): void
    {
        // Before the theme prints anything, so a redirect is still possible.
        add_action('template_redirect', [$this, 'handle'], 1);
    }

    public function handle(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || !isset($_POST[self::MARKER])) {
            return;
        }

        $input  = wp_unslash($_POST);
        $source = sanitize_key((string) ($input[self::SOURCE] ?? 'contact')) ?: 'contact';

        if (!SpamGuard::looksHuman($input)) {
            // Look successful so a bot learns nothing, but store nothing.
            $this->finish($source);
        }

        FormState::keepToken((string) $input[SpamGuard::TOKEN]);

        $result = LeadForm::validate($input);
        if ($result['errors'] !== []) {
            FormState::fail($result['errors'], $result['values']);
            return;
        }

        if (SpamGuard::isRateLimited()) {
            FormState::fail([], $result['values'], __('Too many requests in a short time. Please try again later.', 'rashid'));
            return;
        }

        $id = LeadStore::create($result['values'], $source, $this->pageUrl());
        if (is_wp_error($id)) {
            FormState::fail([], $result['values'], __('Sorry, your message could not be saved. Please try again.', 'rashid'));
            return;
        }

        SpamGuard::recordSubmission();
        Notifier::send(Lead::fromPost($id));
        $this->finish($source);
    }

    /**
     * Redirect back to the form with the "sent" flag.
     */
    private function finish(string $source): never
    {
        $url = add_query_arg(FormState::SENT_ARG, $source, $this->pageUrl()) . '#rashid-form-' . $source;
        wp_safe_redirect($url);
        exit;
    }

    /**
     * The page the form was on, without its query string.
     */
    private function pageUrl(): string
    {
        $path = (string) strtok((string) wp_unslash($_SERVER['REQUEST_URI'] ?? '/'), '?');

        // home_url() already holds the folder WordPress lives in (if any); drop it from the request path.
        $base = untrailingslashit((string) wp_parse_url(home_url(), PHP_URL_PATH));

        return home_url(substr($path, strlen($base)));
    }
}
