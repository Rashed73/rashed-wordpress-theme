<?php
/**
 * The lead edit screen: the request itself (read-only) plus the two things the
 * owner edits, status and private notes.
 *
 * @package Rashid
 */

declare(strict_types=1);

namespace Rashid\Leads\Admin;

use Rashid\Leads\Lead;
use Rashid\Leads\LeadFields;
use Rashid\Module;
use WP_Post;

final class LeadDetailsBox implements Module
{
    private const NONCE_ACTION = 'rashid_lead_details';
    private const NONCE_FIELD  = 'rashid_lead_nonce';
    private const FIELD_STATUS = 'rashid_lead_status';
    private const FIELD_NOTES  = 'rashid_lead_notes';

    public function register(): void
    {
        add_action('add_meta_boxes', [$this, 'addBoxes']);
        add_action('save_post_' . LeadFields::POST_TYPE, [$this, 'save']);
    }

    public function addBoxes(): void
    {
        add_meta_box('rashid-lead-request', __('Request', 'rashid'), [$this, 'renderRequest'], LeadFields::POST_TYPE, 'normal', 'high');
        add_meta_box('rashid-lead-follow-up', __('Follow-up', 'rashid'), [$this, 'renderFollowUp'], LeadFields::POST_TYPE, 'side', 'high');
    }

    public function renderRequest(WP_Post $post): void
    {
        $lead  = Lead::fromPost($post);
        $phone = preg_replace('/[^0-9+]/', '', $lead->phone) ?? '';
        ?>
        <table class="form-table rashid-lead" role="presentation">
          <tr><th><?php esc_html_e('Name', 'rashid'); ?></th><td><?php echo esc_html($lead->name); ?></td></tr>
          <tr><th><?php esc_html_e('Email', 'rashid'); ?></th><td><a href="mailto:<?php echo esc_attr($lead->email); ?>"><?php echo esc_html($lead->email); ?></a></td></tr>
          <tr><th><?php esc_html_e('Phone', 'rashid'); ?></th>
              <td><?php echo $lead->phone !== '' ? '<a href="tel:' . esc_attr($phone) . '">' . esc_html($lead->phone) . '</a>' : '—'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped inline. ?></td></tr>
          <tr><th><?php esc_html_e('Form', 'rashid'); ?></th><td><?php echo esc_html($lead->source); ?></td></tr>
          <tr><th><?php esc_html_e('Page', 'rashid'); ?></th><td><?php echo esc_html($lead->page); ?></td></tr>
          <tr><th><?php esc_html_e('Language', 'rashid'); ?></th><td><?php echo esc_html(strtoupper($lead->language)); ?></td></tr>
          <tr><th><?php esc_html_e('Received', 'rashid'); ?></th><td><?php echo esc_html($lead->date); ?></td></tr>
          <tr><th><?php esc_html_e('Message', 'rashid'); ?></th><td class="rashid-lead__message"><?php echo nl2br(esc_html($lead->message)); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped, then line breaks added. ?></td></tr>
        </table>
        <?php
    }

    public function renderFollowUp(WP_Post $post): void
    {
        $lead = Lead::fromPost($post);
        wp_nonce_field(self::NONCE_ACTION, self::NONCE_FIELD);
        ?>
        <p>
          <label for="<?php echo esc_attr(self::FIELD_STATUS); ?>"><strong><?php esc_html_e('Status', 'rashid'); ?></strong></label><br>
          <select id="<?php echo esc_attr(self::FIELD_STATUS); ?>" name="<?php echo esc_attr(self::FIELD_STATUS); ?>">
            <?php foreach (LeadFields::statuses() as $value => $label) : ?>
              <option value="<?php echo esc_attr($value); ?>" <?php selected($lead->status, $value); ?>><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
          </select>
        </p>
        <p>
          <label for="<?php echo esc_attr(self::FIELD_NOTES); ?>"><strong><?php esc_html_e('Private notes', 'rashid'); ?></strong></label>
          <textarea id="<?php echo esc_attr(self::FIELD_NOTES); ?>" name="<?php echo esc_attr(self::FIELD_NOTES); ?>" rows="6" class="widefat"><?php echo esc_textarea($lead->notes); ?></textarea>
        </p>
        <?php
    }

    public function save(int $postId): void
    {
        $nonce = isset($_POST[self::NONCE_FIELD]) ? wp_unslash($_POST[self::NONCE_FIELD]) : '';
        if (wp_verify_nonce($nonce, self::NONCE_ACTION) === false || !current_user_can('edit_post', $postId)) {
            return;
        }

        $status = sanitize_key(wp_unslash($_POST[self::FIELD_STATUS] ?? ''));
        if (LeadFields::isStatus($status)) {
            update_post_meta($postId, LeadFields::META_STATUS, $status);
        }

        update_post_meta($postId, LeadFields::META_NOTES, sanitize_textarea_field(wp_unslash($_POST[self::FIELD_NOTES] ?? '')));
    }
}
