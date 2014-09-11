<?php if ( awpcp_request_param( 'register', false ) ): ?>
	<?php echo awpcp_print_message( __( 'Please check your email for the password and then return to log in.', 'AWPCP' ) ); ?>
<?php elseif ( awpcp_request_param( 'reset', false ) ): ?>
	<?php echo awpcp_print_message( __( 'Please check your email to reset your password.', 'AWPCP' ) ); ?>
<?php elseif ( $message ): ?>
	<?php echo awpcp_print_message( $message ); ?>
<?php endif; ?>

<div class="awpcp-login-form">
	<?php wp_login_form( array( 'redirect' => $redirect ) ); ?>

	<p id="nav" class="nav">
	<?php if ( isset($_GET['checkemail']) && in_array( $_GET['checkemail'], array('confirm', 'newpass') ) ) : ?>
	<?php elseif ( get_option('users_can_register') ) : ?>
	<a href="<?php echo esc_url( $register_url ); ?>"><?php _e( 'Register', 'AWPCP' ); ?></a> |
	<a href="<?php echo esc_url( $lost_password_url ); ?>" title="<?php esc_attr_e( 'Password Lost and Found', 'AWPCP' ); ?>"><?php _e( 'Lost your password?', 'AWPCP' ); ?></a>
	<?php else : ?>
	<a href="<?php echo esc_url( $lost_password_url ); ?>" title="<?php esc_attr_e( 'Password Lost and Found', 'AWPCP' ); ?>"><?php _e( 'Lost your password?', 'AWPCP' ); ?></a>
	<?php endif; ?>
	</p>
</div>
