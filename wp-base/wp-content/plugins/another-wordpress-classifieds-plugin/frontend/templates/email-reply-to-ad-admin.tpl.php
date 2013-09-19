<?php // emails are sent in plain text, blank lines in templates and spaces at 
      // the end of the lineare required ?>
<?php _e('Someone responded to one of the Ads in your website.'); ?>


<?php _e("Contacting about", "AWPCP"); ?>: <?php echo $ad_title; ?> 
<?php echo $ad_url; ?> 

<?php _ex("Message", 'reply email', "AWPCP"); ?>:

    <?php echo $message; ?> 

<?php _e("Reply to", "AWPCP"); ?>: <?php echo $sender_name; ?>, <?php echo $sender_email; ?>


<?php echo $nameofsite; ?> 
<?php echo home_url(); ?>
