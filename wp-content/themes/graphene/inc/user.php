<?php
if ( ! function_exists( 'graphene_author_social_links' ) ) :
/**
 * Display author's social links
 */
function graphene_author_social_links( $user_id, $display_email = true ){
	$userdata = get_userdata( $user_id );
	$user_social = get_user_meta( $user_id, 'graphene_author_social', true );
	
	if ( ! $userdata ) return;
	?>
    <ul class="author-social">
    	<?php if ( $user_social ) : foreach ( $user_social as $social_media => $url ) : if ( ! $url ) continue; ?>
        	<li><a href="<?php echo esc_url( $url ); ?>"><i class="fa fa-<?php echo esc_attr( $social_media ); ?>"></i></a></li>
        <?php endforeach; endif; ?>
        
		<?php if ( $display_email && ! get_user_meta( $user_id, 'graphene_author_hide_email', true ) ) : ?>
	        <li><a href="mailto:<?php echo esc_attr( $userdata->user_email ); ?>"><i class="fa fa-envelope-o"></i></a></li>
        <?php endif; ?>
    </ul>
    <?php
}
 endif;


/**
* Replace the user's Gravatar with custom user avatar
*/
function graphene_pre_get_avatar_data( $args, $id_or_email ) {

	$user_id = 0;

	/* If this is a comment object, check if user is registered */
	if ( is_object( $id_or_email ) && isset( $id_or_email->comment_ID ) ) {
		if ( $id_or_email->user_id ) $user_id = $id_or_email->user_id;
		else {
			$user = get_user_by( 'email', $id_or_email->comment_author_email );
			if ( $user ) $user_id = $user->ID;
		}
	
	} else if ( is_numeric( $id_or_email ) ) {
		/* If this is the user ID, set it as such */
		$user_id = $id_or_email;

	} else if ( is_email( $id_or_email ) ) {
		/* If this is email, see if it's a registered user */
		$user = get_user_by( 'email', $id_or_email );
		if ( $user ) $user_id = $user->ID;
	}

	/* Get the custom user image, if available */
	if ( $user_id ) {
		$user_image = get_user_meta( $user_id, 'graphene_author_imgurl', true );
		if ( $user_image ) $args['url'] = esc_url( $user_image );
	}

	return $args;
}
add_filter( 'pre_get_avatar_data', 'graphene_pre_get_avatar_data', 10, 2 );