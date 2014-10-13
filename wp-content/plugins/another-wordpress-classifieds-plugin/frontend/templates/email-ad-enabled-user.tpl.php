<?php // emails are sent in plain text, trailing whitespace are required for proper formatting ?>
<?php echo sprintf(__('Hello %s,', 'AWPCP'), $ad->ad_contact_name) ?> 

<?php $message = __('Your Ad "%s" was recently approved by the admin. You should be able to see the Ad published here: %s.', 'AWPCP'); ?>
<?php echo sprintf( $message, $ad->get_title(), urldecode( url_showad( $ad->ad_id ) ) ); ?> 

<?php echo awpcp_get_blog_name() ?> 
<?php echo home_url() ?> 
