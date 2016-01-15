<?php // emails are sent in plain text, all blank lines in templates are required ?>
<?php echo $introduction; ?>: 

<?php _e( 'Total ads found sharing your email address', 'another-wordpress-classifieds-plugin' ); ?>: <?php echo count( $ads ); ?> 

<?php foreach ( $ads as $ad ): ?>
<?php echo $ad->get_title(); ?> 
<?php _e( 'Access Key', 'another-wordpress-classifieds-plugin' ); ?>: <?php echo $ad->get_access_key(); ?> 
 
<?php endforeach; ?>

<?php echo awpcp_get_blog_name(); ?> 
<?php echo home_url(); ?> 
