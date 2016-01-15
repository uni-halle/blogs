<?php echo $introduction; ?> 
 
<?php _e( 'Listing Details are below:', 'another-wordpress-classifieds-plugin' ); ?> 
 
<?php _e( 'Title', 'another-wordpress-classifieds-plugin' ); ?>: <?php echo $listing->get_title(); ?> 
<?php _e( 'Posted on', 'another-wordpress-classifieds-plugin' ); ?>: <?php echo $listing->get_start_date(); ?> 
<?php _e( 'Expires on', 'another-wordpress-classifieds-plugin' ); ?>: <?php echo $listing->get_end_date(); ?> 
 
<?php echo sprintf( __( 'You can renew your Ad visiting this link: %s', 'another-wordpress-classifieds-plugin' ), $renew_url ); ?> 
