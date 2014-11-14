<?php echo get_awpcp_option('listingaddedbody') ?> 

<?php _e("Listing Title", "AWPCP") ?>: <?php echo $ad->ad_title ?> 
<?php _e("Listing URL", "AWPCP") ?>: <?php echo urldecode( url_showad( $ad->ad_id ) ); ?> 
<?php _e("Listing ID", "AWPCP") ?>: <?php echo $ad->ad_id ?> 
<?php _e("Listing Edit Email", "AWPCP") ?>: <?php echo $ad->ad_contact_email ?> 
<?php if ( get_awpcp_option( 'include-ad-access-key' ) ): ?>
<?php _e( "Listing Edit Key", "AWPCP" ); ?>: <?php echo $ad->get_access_key(); ?> 
<?php endif; ?>

<?php $blog_name = awpcp_get_blog_name(); ?>

<?php if ($transaction): ?>
<?php echo sprintf( __( "%s Transaction", "AWPCP" ), $blog_name ); ?>: <?php echo $transaction->id ?> 
<?php   if ($transaction->get('txn-id')): ?>
<?php _e("Payment Transaction", "AWPCP")?>: <?php echo $transaction->get('txn-id') ?> 
<?php   endif ?>
<?php   if ( $show_total_amount ): ?>
<?php echo esc_html( __( 'Order Total', 'AWPCP' ) ); ?> (<?php echo esc_html( $currency_code ); ?>): <?php echo esc_html( awpcp_format_money( $total_amount ) ); ?> 
<?php   endif; ?>
<?php   if ( $show_total_credits ): ?>
<?php echo esc_html( __( 'Order Total (credits)', 'AWPCP' ) ); ?>: <?php echo esc_html( $total_credits ); ?> 
<?php   endif; ?>
<?php endif ?>


<?php if (!empty($message)): ?>
<?php _e('Additional Details', 'AWPCP') ?> 

<?php echo $message ?> 
<?php endif ?>


<?php echo sprintf(__("If you have questions about your listing contact %s. Thank you for your business.", 'AWPCP'), $admin_email) ?> 

<?php echo $blog_name; ?> 
<?php echo home_url(); ?> 
