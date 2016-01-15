<?php // emails are sent in plain text, trailing whitespace are required for proper formatting ?>
<?php _e('Dear Administrator', 'another-wordpress-classifieds-plugin') ?>, 

<?php _e("There was a problem during a customer's attempt to submit payment. Transaction details are shown below", 'another-wordpress-classifieds-plugin') ?> 

<?php echo sprintf("\t%s", $message); ?> 

<?php if ($user): ?>
<?php _e('User Name', 'another-wordpress-classifieds-plugin') ?>: <?php echo $user->display_name ?> 
<?php _e('User Login', 'another-wordpress-classifieds-plugin') ?>: <?php echo $user->user_login ?> 
<?php _e('User Email', 'another-wordpress-classifieds-plugin') ?>: <?php echo $user->user_email ?> 
<?php endif ?>

<?php if ($transaction): ?>
<?php echo esc_html( __( 'Payment Term Type', 'another-wordpress-classifieds-plugin' ) ); ?>: <?php echo esc_html( $transaction->get( 'payment-term-type' ) ); ?> 
<?php _e('Payment Term ID', 'another-wordpress-classifieds-plugin') ?>: <?php echo esc_html( $transaction->get( 'payment-term-id' ) ); ?> 
<?php _e('Payment transaction ID', 'another-wordpress-classifieds-plugin') ?>: <?php echo esc_html( $transaction->get( 'txn-id' ) ); ?> 
<?php endif ?>
