<?php $message = __('The Ad "%s" is awaiting approval. You can approve the Ad going to the Manage Listings section and clicking the "Enable" action shown on top. Click here to continue: %s', 'AWPCP') ?>
<?php echo sprintf($message, $ad->get_title(), $url) ?> 

<?php echo awpcp_get_blog_name() ?> 
<?php echo home_url() ?> 
