<?php
/**
 * Contract for a self-contained piece of theme behaviour.
 *
 * A module hooks itself into WordPress inside register() and does nothing else
 * at construction time, so booting the theme stays cheap and predictable.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid;

interface Module
{
    /**
     * Attach this module's actions and filters.
     */
    public function register(): void;
}
