<?php // emails are sent in plain text, trailing whitespace are required for proper formatting ?>
<?php echo get_awpcp_option('paymentabortedbodymessage') ?> 

<?php _e('Additional Details', 'AWPCP') ?> 

<?php echo sprintf("\t%s", $message); ?> 

<?php if ($transaction): ?>
<?php _e('Payment transaction ID', 'AWPCP') ?>: <?php echo $transaction->id ?> 
<?php endif ?>

<?php echo awpcp_get_blog_name() ?> 
<?php echo home_url() ?> 
