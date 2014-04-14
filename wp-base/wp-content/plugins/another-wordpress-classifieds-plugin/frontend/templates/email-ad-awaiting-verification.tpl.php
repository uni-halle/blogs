<?php // emails are sent in plain text, trailing whitespace are required for proper formatting ?>
<?php echo sprintf( __( 'Hello %s,', 'AWPCP' ), $contact_name ); ?> 

<?php $message = __( 'Your recently posted the Ad "%s" to %s. In order to complete the posting process you have to verify your email address. Please click the link below to complete the verification process. You will be redirected to the website where you can see your Ad.', 'AWPCP' ); ?>
<?php echo sprintf( $message, $ad_title, awpcp_get_blog_name() ) ?> 

<?php echo $verification_link; ?> 

<?php echo __( 'After you verify your email address, the administrator will be notified about the new Ad. If moderation is enabled, your Ad will remain in a disabled status until the administrator approves it.', 'AWPCP' ); ?> 

<?php echo awpcp_get_blog_name(); ?> 
<?php echo home_url(); ?> 
