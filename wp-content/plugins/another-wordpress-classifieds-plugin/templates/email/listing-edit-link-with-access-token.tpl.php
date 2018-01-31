<?php echo esc_html( str_replace( '<contact-name>', $contact_name, _x( 'Hi <contact-name>,', 'edit link email', 'another-wordpress-classifieds-plugin' ) ) ); ?> 

<?php echo esc_html( str_replace( '<listing-title>', $listing_title, _x( 'The access key and edit link for the listing <listing-title> are shown below:', 'edit link email', 'another-wordpress-classifieds-plugin' ) ) ); ?> 

<?php echo esc_html( str_replace( '<access-key>', $access_key, _x( 'Access Key: <access-key>', 'edit link email', 'another-wordpress-classifieds-plugin' ) ) ); ?> 
<?php echo esc_html( str_replace( '<email-address>', $email_address, _x( 'Email Address: <email-address>', 'edit link email', 'another-wordpress-classifieds-plugin' ) ) ); ?> 
<?php echo esc_html( str_replace( '<edit-link>', $edit_link, _x( 'Edit Link: <edit-link>', 'edit link email', 'another-wordpress-classifieds-plugin' ) ) ); ?> 

<?php echo esc_html_x( 'The edit link will expire after 24 hours. If you use the link after it has expired, a new one will be delivered to your email address automatically.', 'edit link email', 'another-wordpress-classifieds-plugin' ); ?> 

<?php echo esc_html_x( "You are receiving this message because you tried to edit this listing using an expired link or because you or the administrator requested the access key to be sent. If that's not the case, please ignore this message, or contact the adiministrator if you have received similar messages today.", 'edit link email', 'another-wordpress-classifieds-plugin' ); ?> 

<?php echo awpcp_get_blog_name(); ?> 
<?php echo home_url(); ?> 
