<h3><?php _ex('Complete Payment', 'add credit checkout step', 'AWPCP') ?></h3>

<?php foreach ($messages as $message): ?>
    <?php echo awpcp_print_message($message) ?>
<?php endforeach ?>

<?php echo $payments->render_checkout_page($transaction, $hidden) ?>
