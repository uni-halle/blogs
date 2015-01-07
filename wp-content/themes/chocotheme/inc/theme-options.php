<?php

add_action( 'admin_init', 'choco_theme_options_init' );
add_action( 'admin_menu', 'choco_theme_options_add_page' );

/**
 * Add theme options page styles
 */
function choco_admin_enqueue_scripts( $hook_suffix ) {
	 wp_enqueue_style( 'choco', get_template_directory_uri() . '/inc/theme-options.css', '', '20110224' );
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'choco_admin_enqueue_scripts' );

/**
 * Init plugin options to white list our options
 */
function choco_theme_options_init(){
	register_setting( 'choco_options', 'choco_theme_options', 'choco_theme_options_validate' );
}

/**
 * Load up the menu page
 */
function choco_theme_options_add_page() {
	add_theme_page( __( 'Theme Options', 'choco' ), __( 'Theme Options', 'choco' ), 'edit_theme_options', 'theme_options', 'choco_theme_options_do_page' );
}

/**
 * Return array for our color schemes
 */
function choco_color_schemes() {
	$color_schemes = array(
		'default' => array(
			'value' =>	'default',
			'label' => __( 'Default', 'choco' )
		),
		'darkgray' => array(
			'value' =>	'darkgray',
			'label' => __( 'Dark Gray', 'choco' )
		),
		'red' => array(
			'value' =>	'red',
			'label' => __( 'Red', 'choco' )
		),
	);

	return $color_schemes;
}

/**
 * Create the options page
 */
function choco_theme_options_do_page() {

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	?>
	<div class="wrap">
		<?php echo "<h2>" . wp_get_theme() . __( ' Theme Options', 'choco' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'choco' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'choco_options' ); ?>
			<?php $options = choco_get_options(); ?>

			<table class="form-table">

				<?php
				/**
				 * Choco Color Scheme
				 */
				?>
				<tr valign="top" id="choco-colors"><th scope="row"><?php _e( 'Color Scheme', 'choco' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Color Scheme', 'choco' ); ?></span></legend>
						<?php
							if ( ! isset( $checked ) )
								$checked = '';
							foreach ( choco_color_schemes() as $option ) {
								$radio_setting = $options['color_scheme'];

								if ( '' != $radio_setting ) {
									if ( $options['color_scheme'] == $option['value'] ) {
										$checked = "checked=\"checked\"";
									} else {
										$checked = '';
									}
								}
								?>
								<div class="layout">
								<label class="description">
									<input type="radio" name="choco_theme_options[color_scheme]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php echo $checked; ?> />
									<span>
										<img src="<?php echo get_template_directory_uri(); ?>/images/colors/<?php echo $option['value']; ?>.png"/>
										<?php echo $option['label']; ?>
									</span>
								</label>
								</div>
								<?php
							}
						?>
						</fieldset>
					</td>
				</tr>
			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Options', 'choco' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function choco_theme_options_validate( $input ) {

	// Our radio option must actually be in our array of radio options
	if ( ! isset( $input['color_scheme'] ) )
		$input['color_scheme'] = null;
	if ( ! array_key_exists( $input['color_scheme'], choco_color_schemes() ) )
		$input['color_scheme'] = null;

	return $input;
}

// adapted from http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/