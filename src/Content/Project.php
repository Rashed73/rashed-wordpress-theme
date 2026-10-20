<?php
/**
 * Read-only view of one project's structured data.
 *
 * Templates ask this object for values instead of reading post meta directly,
 * so the storage format can change without touching any template.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Content;

final class Project
{
    /**
     * @param array<string, string> $values Meta values keyed by ProjectFields constant.
     */
    private function __construct(
        private readonly int $id,
        private readonly array $values
    ) {
    }

    public static function fromPost(int $postId): self
    {
        $values = [];
        foreach (ProjectFields::keys() as $key) {
            $values[$key] = (string) get_post_meta($postId, $key, true);
        }

        return new self($postId, $values);
    }

    public function id(): int
    {
        return $this->id;
    }

    /**
     * Does the project have a main screenshot whose file is actually on the server?
     * (A project can point to an image that was lost, which would show as a broken picture.)
     */
    public function hasImage(): bool
    {
        return self::imageExists($this->id);
    }

    /**
     * Does this post's featured image exist as a file?
     */
    public static function imageExists(int $postId): bool
    {
        $thumbnailId = (int) get_post_thumbnail_id($postId);
        if ($thumbnailId === 0) {
            return false;
        }

        $file = get_attached_file($thumbnailId);

        return is_string($file) && $file !== '' && file_exists($file);
    }

    public function client(): string
    {
        return $this->values[ProjectFields::CLIENT];
    }

    public function industry(): string
    {
        return $this->values[ProjectFields::DOMAIN];
    }

    public function role(): string
    {
        return $this->values[ProjectFields::ROLE];
    }

    public function developer(): string
    {
        return $this->values[ProjectFields::DEVELOPER];
    }

    public function designer(): string
    {
        return $this->values[ProjectFields::DESIGNER];
    }

    public function website(): string
    {
        return $this->values[ProjectFields::WEBSITE];
    }

    /**
     * Technologies as a clean list.
     *
     * @return string[]
     */
    public function stack(): array
    {
        $parts = array_map('trim', explode(',', $this->values[ProjectFields::STACK]));

        return array_values(array_filter($parts, static fn (string $item): bool => $item !== ''));
    }

    /**
     * Category names keyed by slug.
     *
     * @return array<string, string>
     */
    public function categories(): array
    {
        $terms = get_the_terms($this->id, ProjectPostType::TAXONOMY);
        if (!is_array($terms)) {
            return [];
        }

        $categories = [];
        foreach ($terms as $term) {
            $categories[$term->slug] = $term->name;
        }

        return $categories;
    }

    /**
     * Whether there is anything to show in the "at a glance" box.
     */
    public function hasDetails(): bool
    {
        return $this->facts() !== [] || $this->stack() !== [] || $this->website() !== '';
    }

    /**
     * Name of the first category, or an empty string.
     */
    public function primaryCategory(): string
    {
        $names = array_values($this->categories());

        return $names[0] ?? '';
    }

    /**
     * The label/value rows shown in the "at a glance" box. Empty values are left out.
     *
     * @return array<int, array{label: string, value: string}>
     */
    public function facts(): array
    {
        $rows = [
            [__('Client', 'rashid'), $this->client()],
            [__('Industry', 'rashid'), $this->industry()],
            [__('Role', 'rashid'), $this->role()],
            [__('Developer', 'rashid'), $this->developer()],
            [__('Designer', 'rashid'), $this->designer()],
        ];

        $facts = [];
        foreach ($rows as [$label, $value]) {
            if ($value !== '') {
                $facts[] = ['label' => $label, 'value' => $value];
            }
        }

        return $facts;
    }

    /**
     * Host name of the project's website, for placeholders and labels.
     */
    public function websiteHost(): string
    {
        $host = wp_parse_url($this->website(), PHP_URL_HOST);

        return is_string($host) ? $host : '';
    }
}
