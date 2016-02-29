<?php // emails are sent in plain text, blank lines in templates and spaces at 
      // the end of the lineare required; ?>
<?php echo $body; ?>


<?php _e("Contacting about", 'another-wordpress-classifieds-plugin'); ?>: <?php echo $ad_title; ?> 
<?php echo urldecode( $ad_url ); ?> 

<?php _ex("Message", 'reply email', 'another-wordpress-classifieds-plugin'); ?>:

    <?php echo $message; ?> 

<?php _e("Reply to", 'another-wordpress-classifieds-plugin'); ?>: <?php echo $sender_name; ?>, <?php echo $sender_email; ?>


<?php echo $nameofsite; ?> 
<?php echo home_url(); ?>
