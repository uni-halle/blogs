<?php // emails are sent in plain text, blank lines in templates and spaces at 
      // the end of the lineare required ?>
<?php _e('Someone responded to one of the Ads in your website.', 'another-wordpress-classifieds-plugin'); ?>


<?php echo sprintf( __( 'Contact name: %s', 'another-wordpress-classifieds-plugin' ), $sender_name ); ?> 
<?php echo sprintf( __( 'Contact email: %s', 'another-wordpress-classifieds-plugin' ), $sender_email ); ?> 


<?php _e("Contacting about", 'another-wordpress-classifieds-plugin'); ?>: <?php echo $ad_title; ?> 
<?php echo urldecode( $ad_url ); ?> 

<?php _ex("Message", 'reply email', 'another-wordpress-classifieds-plugin'); ?>:

    <?php echo $message; ?> 


<?php echo $nameofsite; ?> 
<?php echo home_url(); ?>
