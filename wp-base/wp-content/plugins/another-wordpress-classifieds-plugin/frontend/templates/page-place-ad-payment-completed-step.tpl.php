<h2><?php echo $payments->render_payment_completed_page_title($transaction) ?></h2>

<?php awpcp_print_messages(); ?>

<?php foreach ($messages as $message): ?>
    <?php echo awpcp_print_message($message) ?>
<?php endforeach ?>

<?php echo $payments->render_payment_completed_page($transaction, $url, $hidden) ?>
