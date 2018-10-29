<?php
/**
 * This is the function to display the custom user field in the user admin page
*/
function graphene_show_custom_user_fields( $user ){ 
    if ( ! is_admin() ) return;
    global $current_user; 

    wp_enqueue_media();
?>
    <h3><?php _e( 'Graphene-specific User Profile Information', 'graphene' ); ?></h3>
    <p><?php _e( 'The settings defined here are used only with the Graphene theme.', 'graphene' ); ?></p>
    <table class="form-table">
        <tr>
            <th>
                <label for="author_imgurl"><?php _e( 'Author profile image URL', 'graphene' ); ?></label><br />
                <?php /* translators: %s will be replaced by a link to the Gravatar website (http://www.gravatar.com) */ ?>
            </th>
            <td>
            	<?php $author_imgurl = get_user_meta( $user->ID, 'graphene_author_imgurl', true ); ?>
            	<img src="<?php echo $author_imgurl; ?>" class="author-image-preview" style="max-height:150px;max-width:150px" alt="" /><br />

                <input type="text" name="author_imgurl" id="author_imgurl" value="<?php echo esc_attr( $author_imgurl ); ?>" size="80" />
                <input type="button" class="button-primary" value="<?php _e( 'Upload Image', 'graphene' ); ?>" id="graphene_author_imgurl_upload" data-uploader_title="<?php _e( 'Author profile image', 'graphene' ); ?>" data-uploader_button_text="<?php _e( 'Select image', 'graphene' ); ?>" /><br />
                <span class="description">
                	<?php printf( __( 'You can specify the image to be displayed as the author\'s profile image in the Author\'s page. If no URL is defined here, the author\'s %s will be used.', 'graphene' ), '<a href="http://www.gravatar.com">Gravatar</a>' ); ?><br />
                	<?php _e( '<strong>Important: </strong>Image width must be less than or equal to <strong>150px</strong>.', 'graphene' ); ?>
                </span>
                
                <br /><br />
                <input type="checkbox" name="graphene_author_imgurl_as_avatar" id="graphene_author_imgurl_as_avatar" value="1" <?php checked( get_user_meta( $user->ID, 'graphene_author_imgurl_as_avatar', true ), true ); ?> /> 
                <label for="graphene_author_imgurl_as_avatar"><?php _e( 'Use this image as your comments avatar', 'graphene' ); ?></label>


                <script type="text/javascript">
                	jQuery(document).ready(function($){
						var file_frame;
						$('#graphene_author_imgurl_upload').on('click', function( event ){
							event.preventDefault();

							if ( file_frame ) {
								file_frame.open();
								return;
							}

							file_frame = wp.media.frames.file_frame = wp.media({
								title 	: $(this).data('uploader_title'),
								button 	: {
									text: $(this).data('uploader_button_text'),
								},
								multiple: false
							});

							file_frame.on( 'select', function() {
								attachment = file_frame.state().get('selection').first().toJSON();
								$('#author_imgurl').val(attachment.sizes.thumbnail.url);
								$('#author_imgurl').trigger('change');
							});

							file_frame.open();
						});

						$('#author_imgurl').on('change',function(){
							$('.author-image-preview').attr('src', $(this).val());
						});
					});
                </script>
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
 * This is the function to display the custom user field in the frontend (example: for bbPress)
*/
function graphene_show_custom_user_fields_frontend( $user ){ 
    if ( is_admin() ) return;
    global $current_user; 
?>
    <legend><?php _e( 'Additional user information', 'graphene' ); ?></legend>

    <div class="form-group">
        <label for="author_imgurl" class="col-sm-3 control-label"><?php _e( 'URL to profile image', 'graphene' ); ?></label>
        <?php /* translators: %s will be replaced by a link to the Gravatar website (http://www.gravatar.com) */ ?>
        <div class="col-sm-9">
            <input class="form-control" type="text" name="author_imgurl" id="author_imgurl" value="<?php echo esc_attr( get_user_meta( $user->ID, 'graphene_author_imgurl', true ) ); ?>" />
            <span class="description help-block"><?php printf( __( 'If no URL is defined here, the your %s will be used.', 'graphene' ), '<a href="http://www.gravatar.com">Gravatar</a>' ); ?></span>
            <?php /* translators: %s will be replaced by 'http://' wrapped in <code> tags */ ?>
            <span class="description help-block"><?php printf( __( 'Please enter the full URL (including %s) to your profile image.', 'graphene' ), '<code>http://</code>' ); ?></span>
            <span class="description help-block"><?php _e( '<strong>Important: </strong>Image width must be less than or equal to <strong>150px</strong>.', 'graphene' ); ?></span>

            <div class="checkbox">
                <label>
                    <input type="checkbox" name="graphene_author_imgurl_as_avatar" value="1" <?php checked( get_user_meta( $user->ID, 'graphene_author_imgurl_as_avatar', true ), true ); ?> /> 
                    <?php _e( 'Use this image as your comments avatar', 'graphene' ); ?>
                </label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="graphene_author_hide_email" class="col-sm-3 control-label"><?php _e( 'Hide email', 'graphene' ); ?></label>
        <div class="col-sm-9 checkbox">
            <label>
                <input type="checkbox" name="graphene_author_hide_email" value="1" <?php checked( get_user_meta( $user->ID, 'graphene_author_hide_email', true ), true ); ?> /> 
                <?php _e( 'Remove email address from your author bio.', 'graphene' ); ?>
            </label>
        </div>
    </div>

    <div class="form-group">
        <label for="graphene_author_location" class="col-sm-3 control-label"><?php _e( 'Current location', 'graphene' ); ?></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="graphene_author_location" id="graphene_author_location" value="<?php echo esc_attr( get_user_meta( $user->ID, 'graphene_author_location', true ) ); ?>" class="code" size="75" />
            <span class="description help-block">
                <?php _e( 'Will be displayed on your front-facing author profile page. Recommended to use "City, Country" format, e.g. "Lisbon, Portugal".', 'graphene' ); ?>
            </span>
        </div>
    </div>


    <legend><?php _e( 'Social media URLs', 'graphene' ); ?></legend>
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
        <div class="form-group">
            <label for="graphene_author_social_<?php echo $name; ?>" class="col-sm-3 control-label"><?php echo $label; ?></label>
            <div class="col-sm-9">
                <input type="text" name="graphene_author_social[<?php echo $name; ?>]" id="graphene_author_social_<?php echo $name; ?>" value="<?php if ( isset( $current_values[$name] ) ) echo esc_attr( $current_values[$name] ); ?>" class="code form-control" />
            </div>
        </div>
    <?php endforeach; ?>
<?php
}
// Hook the function to add extra field to the user profile page
add_action( 'show_user_profile', 'graphene_show_custom_user_fields_frontend' );
add_action( 'edit_user_profile', 'graphene_show_custom_user_fields_frontend' );


/**
 * This is the function to save the custom user fields we defined above
*/
function graphene_save_custom_user_fields( $user_id ){
    
    if ( ! current_user_can( 'edit_user', $user_id ) )
        return false;
    
    // Updates the custom field and save it as a user meta
    update_user_meta( $user_id, 'graphene_author_imgurl', $_POST['author_imgurl'] );
    update_user_meta( $user_id, 'graphene_author_location', $_POST['graphene_author_location'] );

    $custom_avatar = ( isset( $_POST['graphene_author_imgurl_as_avatar'] ) ) ? true : false;
    update_user_meta( $user_id, 'graphene_author_imgurl_as_avatar', $custom_avatar );

    $hide_email = ( isset( $_POST['graphene_author_hide_email'] ) ) ? true : false;
    update_user_meta( $user_id, 'graphene_author_hide_email', $hide_email );
    
    update_user_meta( $user_id, 'graphene_author_social', $_POST['graphene_author_social'] );
}
// Hook the update function
add_action( 'personal_options_update', 'graphene_save_custom_user_fields' );
add_action( 'edit_user_profile_update', 'graphene_save_custom_user_fields' );


/**
 * Automatically disable author bio display if only a single author is present with empty bio text
 */
function graphene_set_once_author_bio_display(){
    if ( get_option( 'graphene_set_once_author_bio_display', false ) ) return;

    global $graphene_settings;
    if ( ! $graphene_settings['hide_author_bio'] ) {
        $args = array(
            'role__in'  => array(
                'administrator',
                'editor',
                'author',
                'contributor',
            )
        );
        
        $user_query = new WP_User_Query( $args );
        $users = $user_query->get_results();

        if ( ! empty( $users ) && count( $users ) == 1 ) {
            $user = $users[0];
            $user_bio = trim( get_user_meta( $user->ID, 'description', true ) );
            
            if ( ! $user_bio ) {
                $graphene_settings = get_option( 'graphene_settings', array() );
                $graphene_settings['hide_author_bio'] = true;
                update_option( 'graphene_settings', $graphene_settings );

                $graphene_settings = graphene_get_settings();
            }
        }
    }

    update_option( 'graphene_set_once_author_bio_display', 1 );
}
add_action( 'init', 'graphene_set_once_author_bio_display' );