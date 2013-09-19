<?php if (!is_admin()): ?>
    <?php if ($edit): ?>
    <?php echo awpcp_print_message(__("Your changes have been saved.", 'AWPCP')); ?>
    <?php else: ?>
    <?php echo awpcp_print_message(__("Your Ad has been submitted.", "AWPCP")); ?>
    <?php endif; ?>
<?php endif; ?>

<?php awpcp_print_messages(); ?>

<?php foreach ((array) $messages as $message): ?>
    <?php echo awpcp_print_message($message); ?>
<?php endforeach; ?>

<?php echo showad($ad->ad_id, true, true, false); ?>
