<h3><?php echo $payments->render_payment_completed_page_title() ?></h3>

<?php foreach ($messages as $message): ?>
    <?php echo awpcp_print_message($message) ?>
<?php endforeach ?>

<?php echo $payments->render_payment_completed_page($transaction, $url, $hidden) ?>
