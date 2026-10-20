<?php
/**
 * Definition of one field of the lead form.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Leads;

final class FormField
{
    public function __construct(
        public readonly string $name,
        public readonly string $label,
        public readonly string $type,
        public readonly bool $required,
        public readonly int $maxLength,
        public readonly string $autocomplete = ''
    ) {
    }

    /**
     * Name of the HTML input. Prefixed because bare names such as "name" are
     * reserved WordPress query variables and would turn the page into a 404.
     */
    public function inputName(): string
    {
        return 'lead_' . $this->name;
    }
}
