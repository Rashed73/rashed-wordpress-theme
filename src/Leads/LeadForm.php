<?php
/**
 * The fields of the lead form, and the validation of a submission.
 *
 * Pure input handling: it neither stores nor sends anything, so it can be reused
 * and tested on its own.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Leads;

final class LeadForm
{
    /**
     * @return FormField[] Keyed by field name.
     */
    public static function fields(): array
    {
        $fields = [
            new FormField('name', __('Name', 'rashid'), 'text', true, 120, 'name'),
            new FormField('email', __('Email', 'rashid'), 'email', true, 190, 'email'),
            new FormField('phone', __('Phone or WhatsApp (optional)', 'rashid'), 'tel', false, 40, 'tel'),
            new FormField('message', __('How can I help?', 'rashid'), 'textarea', true, 4000),
        ];

        $byName = [];
        foreach ($fields as $field) {
            $byName[$field->name] = $field;
        }

        return $byName;
    }

    /**
     * Clean a raw submission and report what is wrong with it.
     *
     * @param array<string, mixed> $input Unslashed form data.
     * @return array{values: array<string, string>, errors: array<string, string>}
     */
    public static function validate(array $input): array
    {
        $values = [];
        $errors = [];

        foreach (self::fields() as $field) {
            $raw = isset($input[$field->inputName()]) && is_string($input[$field->inputName()]) ? $input[$field->inputName()] : '';
            $value = $field->type === 'textarea' ? sanitize_textarea_field($raw) : sanitize_text_field($raw);
            $value = mb_substr($value, 0, $field->maxLength);

            $values[$field->name] = $value;
            $error = self::check($field, $value);
            if ($error !== '') {
                $errors[$field->name] = $error;
            }
        }

        return ['values' => $values, 'errors' => $errors];
    }

    private static function check(FormField $field, string $value): string
    {
        if ($value === '') {
            return $field->required ? __('Please fill in this field.', 'rashid') : '';
        }

        if ($field->type === 'email' && !is_email($value)) {
            return __('Please enter a valid email address.', 'rashid');
        }

        if ($field->type === 'tel' && !preg_match('/^[0-9+()\-.\s]{5,40}$/', $value)) {
            return __('Please enter a valid phone number.', 'rashid');
        }

        return '';
    }
}
