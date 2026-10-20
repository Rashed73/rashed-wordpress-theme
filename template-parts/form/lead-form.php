<?php
/**
 * The lead form. Receives $args: source (form name) and button (label).
 *
 * Posts back to the same page; Rashid\Leads\SubmissionHandler does the rest.
 *
 * @package Rashid
 *
 * @var array{source: string, button: string} $args
 */

use Rashid\Leads\FormState;
use Rashid\Leads\LeadForm;
use Rashid\Leads\SpamGuard;
use Rashid\Leads\SubmissionHandler;

if (!defined('ABSPATH')) {
    exit;
}

$source = $args['source'];
$id     = 'rashid-form-' . $source;
?>
<div class="lead-form" id="<?php echo esc_attr($id); ?>">
  <?php if (FormState::wasSent($source)) : ?>
    <p class="lead-form__notice lead-form__notice--ok" role="status">
      <?php esc_html_e('Thank you! Your message was sent and you will get a reply soon.', 'rashid'); ?>
    </p>
  <?php else : ?>
    <form method="post" action="<?php echo esc_url(remove_query_arg(FormState::SENT_ARG)); ?>#<?php echo esc_attr($id); ?>" novalidate>
      <?php if (FormState::notice() !== '') : ?>
        <p class="lead-form__notice lead-form__notice--error" role="alert"><?php echo esc_html(FormState::notice()); ?></p>
      <?php endif; ?>

      <?php foreach (LeadForm::fields() as $field) :
          $error    = FormState::error($field->name);
          $fieldId  = $id . '-' . $field->name;
          $errorId  = $fieldId . '-error';
          $describe = $error !== '' ? ' aria-describedby="' . esc_attr($errorId) . '" aria-invalid="true"' : '';
          ?>
        <div class="lead-form__field<?php echo $error !== '' ? ' has-error' : ''; ?>">
          <label for="<?php echo esc_attr($fieldId); ?>">
            <?php echo esc_html($field->label); ?><?php echo $field->required ? ' <span aria-hidden="true">*</span>' : ''; ?>
          </label>
          <?php if ($field->type === 'textarea') : ?>
            <textarea id="<?php echo esc_attr($fieldId); ?>" name="<?php echo esc_attr($field->inputName()); ?>" rows="5"
                      maxlength="<?php echo (int) $field->maxLength; ?>"<?php echo $field->required ? ' required' : ''; ?><?php echo $describe; // phpcs:ignore WordPress.Security.EscapeOutput -- built from escaped parts above. ?>><?php echo esc_textarea(FormState::value($field->name)); ?></textarea>
          <?php else : ?>
            <input id="<?php echo esc_attr($fieldId); ?>" name="<?php echo esc_attr($field->inputName()); ?>" type="<?php echo esc_attr($field->type); ?>"
                   value="<?php echo esc_attr(FormState::value($field->name)); ?>" maxlength="<?php echo (int) $field->maxLength; ?>"
                   autocomplete="<?php echo esc_attr($field->autocomplete); ?>"<?php echo $field->required ? ' required' : ''; ?><?php echo $describe; // phpcs:ignore WordPress.Security.EscapeOutput -- built from escaped parts above. ?>>
          <?php endif; ?>
          <?php if ($error !== '') : ?>
            <p class="lead-form__error" id="<?php echo esc_attr($errorId); ?>"><?php echo esc_html($error); ?></p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>

      <?php // Honeypot: hidden from people and from assistive technology; bots fill it. ?>
      <div class="lead-form__trap" aria-hidden="true">
        <label>Website <input type="text" name="<?php echo esc_attr(SpamGuard::HONEYPOT); ?>" tabindex="-1" autocomplete="off"></label>
      </div>
      <input type="hidden" name="<?php echo esc_attr(SpamGuard::TOKEN); ?>" value="<?php echo esc_attr(FormState::token() ?: SpamGuard::issueToken()); ?>">
      <input type="hidden" name="<?php echo esc_attr(SubmissionHandler::MARKER); ?>" value="1">
      <input type="hidden" name="<?php echo esc_attr(SubmissionHandler::SOURCE); ?>" value="<?php echo esc_attr($source); ?>">

      <button type="submit" class="btn"><?php echo esc_html($args['button']); ?></button>
      <p class="lead-form__privacy"><?php esc_html_e('Your details are used only to reply to your request.', 'rashid'); ?></p>
    </form>
  <?php endif; ?>
</div>
