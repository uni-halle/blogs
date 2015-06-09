<?php
    if ( isset( $transaction ) && get_awpcp_option( 'show-create-listing-form-steps' ) ) {
        echo awpcp_render_listing_form_steps( 'finish', $transaction );
    }
?>

<?php if (!is_admin()): ?>
    <?php if ($edit): ?>
    <?php echo awpcp_print_message(__("Your changes have been saved.", 'AWPCP')); ?>
    <?php else: ?>
    <?php echo awpcp_print_message(__("Your Ad has been submitted.", "AWPCP")); ?>
    <?php endif; ?>
<?php endif; ?>

<?php foreach ((array) $messages as $message): ?>
    <?php echo awpcp_print_message($message); ?>
<?php endforeach; ?>

<?php echo showad($ad->ad_id, true, true, false); ?>
