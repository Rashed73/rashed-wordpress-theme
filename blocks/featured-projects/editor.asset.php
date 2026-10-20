<?php
/**
 * Script dependencies for editor.js (read by WordPress when it registers the
 * block's editor script). The script is hand-written, so there is no build step.
 */

return [
    'dependencies' => ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-data', 'wp-i18n', 'wp-server-side-render'],
    'version'      => '0.1.0',
];
