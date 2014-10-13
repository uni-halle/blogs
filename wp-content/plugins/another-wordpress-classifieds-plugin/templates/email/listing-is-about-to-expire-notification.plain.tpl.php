<?php echo $introduction; ?> 
 
<?php _e( 'Listing Details are below:', 'AWPCP' ); ?> 
 
<?php _e( 'Title', 'AWPCP' ); ?>: <?php echo $listing->get_title(); ?> 
<?php _e( 'Posted on', 'AWPCP' ); ?>: <?php echo $listing->get_start_date(); ?> 
<?php _e( 'Expires on', 'AWPCP' ); ?>: <?php echo $listing->get_end_date(); ?> 
 
<?php echo sprintf( __( 'You can renew your Ad visiting this link: %s', 'AWPCP' ), $renew_url ); ?> 
