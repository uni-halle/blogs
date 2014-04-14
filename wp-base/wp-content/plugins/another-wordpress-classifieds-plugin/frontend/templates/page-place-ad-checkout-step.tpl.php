<h2><?php _ex('Complete Payment', 'place ad checkout step', 'AWPCP') ?></h2>

<?php foreach ($messages as $message): ?>
    <?php echo awpcp_print_message($message) ?>
<?php endforeach ?>

<?php echo $payments->render_checkout_page($transaction, $hidden) ?>
