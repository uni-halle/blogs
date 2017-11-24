<?php
if ( ! function_exists( 'graphene_author_social_links' ) ) :
/**
 * Display author's social links
 */
function graphene_author_social_links( $user_id ){
	$userdata = get_userdata( $user_id );
	$user_social = get_user_meta( $user_id, 'graphene_author_social', true );
	
	if ( ! $userdata ) return;
	?>
    <ul class="author-social">
    	<?php if ( $user_social ) : foreach ( $user_social as $social_media => $url ) : if ( ! $url ) continue; ?>
        	<li><a href="<?php echo esc_url( $url ); ?>"><i class="fa fa-<?php echo esc_attr( $social_media ); ?>"></i></a></li>
        <?php endforeach; endif; ?>
        
		<?php if ( ! get_user_meta( $user_id, 'graphene_author_hide_email', true ) ) : ?>
	        <li><a href="mailto:<?php echo esc_attr( $userdata->user_email ); ?>"><i class="fa fa-envelope-o"></i></a></li>
        <?php endif; ?>
    </ul>
    <?php
}
 endif;