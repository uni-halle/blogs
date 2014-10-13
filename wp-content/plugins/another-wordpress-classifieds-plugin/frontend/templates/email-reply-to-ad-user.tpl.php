<?php // emails are sent in plain text, blank lines in templates and spaces at 
      // the end of the lineare required; ?>
<?php echo get_awpcp_option('contactformbodymessage'); ?>


<?php _e("Contacting about", "AWPCP"); ?>: <?php echo $ad_title; ?> 
<?php echo urldecode( $ad_url ); ?> 

<?php _ex("Message", 'reply email', "AWPCP"); ?>:

    <?php echo $message; ?> 

<?php _e("Reply to", "AWPCP"); ?>: <?php echo $sender_name; ?>, <?php echo $sender_email; ?>


<?php echo $nameofsite; ?> 
<?php echo home_url(); ?>
