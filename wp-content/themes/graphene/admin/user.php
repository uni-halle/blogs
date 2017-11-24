<?php

/**
 * This is the function to display the custom user field in the user admin page
*/
function graphene_show_custom_user_fields($user){ global $current_user; ?>
	<h3><?php _e( 'Graphene-specific User Profile Information', 'graphene' ); ?></h3>
    <p><?php _e( 'The settings defined here are used only with the Graphene theme.', 'graphene' ); ?></p>
    <table class="form-table">
    	<tr>
        	<th>
            	<label for="author_imgurl"><?php _e( 'Author profile image URL', 'graphene' ); ?></label><br />
                <?php /* translators: %s will be replaced by a link to the Gravatar website (http://www.gravatar.com) */ ?>
                <small><?php printf( __( 'You can specify the image to be displayed as the author\'s profile image in the Author\'s page. If no URL is defined here, the author\'s %s will be used.', 'graphene' ), '<a href="http://www.gravatar.com">Gravatar</a>' ); ?></small>
            </th>
            <td>
            	<input type="text" name="author_imgurl" id="author_imgurl" value="<?php echo esc_attr( get_user_meta( $user->ID, 'graphene_author_imgurl', true ) ); ?>" size="50" /><br />
                <?php /* translators: %s will be replaced by 'http://' wrapped in <code> tags */ ?>
                <span class="description"><?php printf( __( 'Please enter the full URL (including %s) to your profile image.', 'graphene' ), '<code>http://</code>' ); ?><br /> <?php _e( '<strong>Important: </strong>Image width must be less than or equal to <strong>150px</strong>.', 'graphene' ); ?></span>
            </td>
        </tr>
        <tr>
            <th>
                <label for="graphene_author_hide_email"><?php _e( 'Hide email', 'graphene' ); ?></label><br />
            </th>
            <td>
                <input type="checkbox" name="graphene_author_hide_email" id="graphene_author_hide_email" value="1" <?php checked( get_user_meta( $user->ID, 'graphene_author_hide_email', true ), true ); ?> /> 
                <label for="graphene_author_hide_email"><?php _e( 'Remove email address from your author bio.', 'graphene' ); ?></label>
            </td>
        </tr>
        <tr>
            <th>
                <label for="graphene_author_location"><?php _e( 'Current location', 'graphene' ); ?></label><br />
            </th>
            <td>
                <input type="text" name="graphene_author_location" id="graphene_author_location" value="<?php echo esc_attr( get_user_meta( $user->ID, 'graphene_author_location', true ) ); ?>" class="code" size="75" /><br />
                <span class="description">
                    <?php _e( 'Will be displayed on your front-facing author profile page. Recommended to use "City, Country" format, e.g. "Lisbon, Portugal".', 'graphene' ); ?>
                </span>
            </td>
        </tr>
        <tr>
            <th><?php _e( 'Social media URLs', 'graphene' ); ?></th>
            <td>
                <?php 
                    $social_media = array( 
                        'facebook'      => 'Facebook', 
                        'twitter'       => 'Twitter', 
                        'google-plus'   => 'Google+', 
                        'linkedin'      => 'LinkedIn',
                        'pinterest'     => 'Pinterest',
                        'youtube'       => 'YouTube',
                        'instagram'     => 'Instagram',
                        'github'        => 'Github'
                    );
                    $social_media = apply_filters( 'graphene_author_social_media', $social_media );
                    
                    $current_values = get_user_meta( $user->ID, 'graphene_author_social', true );
                    foreach ( $social_media as $name => $label ) :
                ?>
                    <p>
                        <label for="graphene_author_social_<?php echo $name; ?>" style="display:inline-block;width:100px"><?php echo $label; ?></label>
                        <input type="text" name="graphene_author_social[<?php echo $name; ?>]" id="graphene_author_social_<?php echo $name; ?>" value="<?php if ( isset( $current_values[$name] ) ) echo esc_attr( $current_values[$name] ); ?>" class="code" size="75" />
                    </p>
                <?php endforeach; ?>
            </td>
        </tr>
    </table>
    <?php
}
// Hook the function to add extra field to the user profile page
add_action( 'show_user_profile', 'graphene_show_custom_user_fields' );
add_action( 'edit_user_profile', 'graphene_show_custom_user_fields' );


/**
 * This is the function to save the custom user fields we defined above
*/
function graphene_save_custom_user_fields( $user_id ){
	
	if ( ! current_user_can( 'edit_user', $user_id ) )
		return false;
	
	// Updates the custom field and save it as a user meta
	update_user_meta( $user_id, 'graphene_author_imgurl', $_POST['author_imgurl'] );
    update_user_meta( $user_id, 'graphene_author_location', $_POST['graphene_author_location'] );

    $hide_email = ( isset( $_POST['graphene_author_hide_email'] ) ) ? true : false;
    update_user_meta( $user_id, 'graphene_author_hide_email', $hide_email );
    
    update_user_meta( $user_id, 'graphene_author_social', $_POST['graphene_author_social'] );
}
// Hook the update function
add_action( 'personal_options_update', 'graphene_save_custom_user_fields' );
add_action( 'edit_user_profile_update', 'graphene_save_custom_user_fields' );