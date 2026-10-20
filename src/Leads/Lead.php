<?php
/**
 * One lead, read from its stored record.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Leads;

use WP_Post;

final class Lead
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $phone,
        public readonly string $message,
        public readonly string $source,
        public readonly string $page,
        public readonly string $language,
        public readonly string $status,
        public readonly string $notes,
        public readonly string $date
    ) {
    }

    public static function fromPost(WP_Post|int $post): self
    {
        $post = get_post($post);
        $meta = static fn (string $key): string => (string) get_post_meta($post->ID, $key, true);
        $status = $meta(LeadFields::META_STATUS);

        return new self(
            $post->ID,
            $post->post_title,
            $meta(LeadFields::META_EMAIL),
            $meta(LeadFields::META_PHONE),
            $post->post_content,
            $meta(LeadFields::META_SOURCE),
            $meta(LeadFields::META_PAGE),
            $meta(LeadFields::META_LANGUAGE),
            LeadFields::isStatus($status) ? $status : LeadFields::STATUS_NEW,
            $meta(LeadFields::META_NOTES),
            $post->post_date
        );
    }
}
