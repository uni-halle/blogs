<?php
/**
 * Add theme settings to the Theme Customizer.
 * 
 * @package Origin
 * @subpackage Template
 * @since 0.5
 */

/* Register custom sections, settings, and controls. */
add_action( 'customize_register', 'origin_customize_register' );

/* Output settings CSS into the head. */
add_action( 'wp_head', 'origin_customize_css');

/* Enqueue Google fonts */
add_action( 'wp_enqueue_scripts', 'origin_google_fonts' );

/**
 * Register custom sections, settings, and controls.
 * 
 */
function origin_customize_register( $wp_customize ) {


	/* -------------- S E C T I O N S --------------- */

	/* Section: Typography */
	$wp_customize->add_section( 'origin_typography' , array(
		'title'      => __( 'Typography', 'origin' ),
		'priority'   => 30,
	) );

	/* Section: Custom CSS */
	$wp_customize->add_section( 'origin_custom_css' , array(
		'title'      => __( 'Custom CSS', 'origin' ),
		'priority'   => 250,
	) );		


	/* -------------- S E T T I N G S --------------- */

	/* Setting: Font Family */
	$wp_customize->add_setting( 'origin_font_family' , array(
		'default'     => hybrid_get_setting( 'origin_font_family' ) ? hybrid_get_setting( 'origin_font_family' ) : '',
		'capability'  => 'edit_theme_options',
		'sanitize_callback' => 	'origin_font_family_sanitize'
	) );	

	/* Setting: Font Size */
	$wp_customize->add_setting( 'origin_font_size' , array(
		'default'     => hybrid_get_setting( 'origin_font_size' ) ? hybrid_get_setting( 'origin_font_size' ) : '16',
		'capability'  => 'edit_theme_options',
		'sanitize_callback' => 	'origin_font_size_sanitize'
	) );	

	/* Setting: Link Color */
	$wp_customize->add_setting( 'origin_link_color' , array(
		'default'     => hybrid_get_setting( 'origin_link_color' ) ? hybrid_get_setting( 'origin_link_color' ) : '#dd5424',
		'capability'  => 'edit_theme_options',
		'sanitize_callback' => 	'origin_link_color_sanitize'
	) );

	/* Setting: Custom CSS */
	$wp_customize->add_setting( 'origin_custom_css' , array(
		'default'     => hybrid_get_setting( 'origin_custom_css' ) ? hybrid_get_setting( 'origin_custom_css' ) : '',
		'capability'  => 'edit_theme_options',
		'sanitize_callback' => 	'origin_custom_css_sanitize'
	) );			


	/* -------------- C O N T R O L S --------------- */

	/* Control: Font Family */
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'origin_font_family', array(
		'label'     => __( 'Font Family', 'origin' ),
		'section'   => 'origin_typography',
		'settings'  => 'origin_font_family',
		'type'		=> 'select',
		'choices'	=> array (
			'Bitter'		=> 'Bitter',
			'Georgia' 		=> 'Georgia',
			'Droid Serif' 	=> 'Droid Serif',
			'Helvetica' 	=> 'Helvetica',
			'Istok Web' 	=> 'Istok Web',
			'Verdana' 		=> 'Verdana',
			'Lucida Sans Unicode' => 'Lucida Sans Unicode',
			'Droid Sans' 	=> 'Droid Sans'
			),
		'priority'  => 1
	) ) );		

	/* Control: Font Size */
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'origin_font_size', array(
		'label'     => __( 'Font Size', 'origin' ),
		'section'   => 'origin_typography',
		'settings'  => 'origin_font_size',
		'type'		=> 'select',
		'choices'	=> array (
			'17' 		=> '17',
			'16' 		=> '16',
			'15' 		=> '15',
			'14' 		=> '14',
			'13' 		=> '13',
			'12' 		=> '12'
			),
		'priority'  => 1
	) ) );		

	/* Control: Link Color */
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'origin-link-color', array(
		'label'     => __( 'Link Color', 'origin' ),
		'section'   => 'colors',
		'settings'  => 'origin_link_color',
		'priority'  => 1
	) ) );

	/* Control: Custom CSS */
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'origin-custom-css', array(
		'label'     => __( 'Custom CSS', 'origin' ),
		'section'   => 'origin_custom_css',
		'settings'  => 'origin_custom_css',
		'priority'  => 1
	) ) );			

}

/**
 * Sanitize the "Font Family" setting.
 * 
 */
function origin_font_family_sanitize( $setting, $object ) {

	if ( 'origin_font_family' == $object->id )
		$setting = wp_filter_nohtml_kses( $setting );

	return $setting;
}

/**
 * Sanitize the "Font Size" setting.
 * 
 */
function origin_font_size_sanitize( $setting, $object ) {

	if ( 'origin_font_size' == $object->id )
		$setting = wp_filter_nohtml_kses( $setting );

	return $setting;
}

/**
 * Sanitize the "Link Color" setting.
 * 
 */
function origin_link_color_sanitize( $setting, $object ) {

	if ( 'origin_link_color' == $object->id )
		$setting = wp_filter_nohtml_kses( $setting );

	return $setting;
}

/**
 * Sanitize the "Custom CSS" setting.
 * 
 */
function origin_custom_css_sanitize( $setting, $object ) {

	if ( 'origin_custom_css' == $object->id )
		$setting = wp_filter_nohtml_kses( $setting );

	return $setting;
}

/**
 * Output settings CSS into the head.
 * 
 */
function origin_customize_css() { ?>

	<style type="text/css">

		/* Font size. */
		<?php if ( get_theme_mod( 'origin_font_size' ) ) { // legacy setting ?>
			html { font-size: <?php echo get_theme_mod( 'origin_font_size' ); ?>px; }
		<?php } elseif ( hybrid_get_setting( 'origin_font_size' ) ) { ?>
			html { font-size: <?php echo hybrid_get_setting( 'origin_font_size' ); ?>px; }
		<?php } ?>

		/* Font family. */
		<?php if ( get_theme_mod( 'origin_font_family' ) ) { // legacy setting ?>
			body { font-family: '<?php echo get_theme_mod( 'origin_font_family' ); ?>', serif; }
		<?php } elseif ( hybrid_get_setting( 'origin_font_family' ) ) { ?>
			body { font-family: '<?php echo hybrid_get_setting( 'origin_font_family' ); ?>', serif; }
		<?php } ?>

		/* Link color. */
		<?php if ( get_theme_mod( 'origin_link_color' ) ) { // legacy setting ?>
			a, a:visited, #footer a:hover, .entry-title a:hover { color: <?php echo get_theme_mod( 'origin_link_color' ); ?>; }
			#respond #submit, .button, a.button, .wpcf7-submit, #loginform .button-primary { background-color: <?php echo get_theme_mod( 'origin_link_color' ); ?>; }
		<?php } elseif ( hybrid_get_setting( 'origin_link_color' ) ) { ?>
			a, a:visited, #footer a:hover, .entry-title a:hover { color: <?php echo hybrid_get_setting( 'origin_link_color' ); ?>; }
			#respond #submit, .button, a.button, .wpcf7-submit, #loginform .button-primary { background-color: <?php echo hybrid_get_setting( 'origin_link_color' ); ?>; }
		<?php } ?>
		a:hover, a:focus { color: #000; }

		/* Custom CSS. */
		<?php if ( get_theme_mod( 'origin_custom_css' ) ) { // legacy setting
			echo get_theme_mod( 'origin_custom_css' ) . "\n"; 
		} else {
			echo hybrid_get_setting( 'origin_custom_css' ) . "\n";
		}
		?>
	
	</style>	

<?php }

/**
 * Enqueu Google fonts.
 *
 */
function origin_google_fonts() {
	
	if ( get_theme_mod( 'origin_font_family' ) ) {
		
		switch ( get_theme_mod( 'origin_font_family' ) ) {			
			case 'Bitter':
				wp_enqueue_style( 'font-bitter', 'http://fonts.googleapis.com/css?family=Bitter', false, 1.0, 'screen' );
				break;
			case 'Droid Serif':
				wp_enqueue_style( 'font-droid-serif', 'http://fonts.googleapis.com/css?family=Droid+Serif', false, 1.0, 'screen' );
				break;
			case 'Istok Web':
				wp_enqueue_style( 'font-istok-web', 'http://fonts.googleapis.com/css?family=Istok+Web', false, 1.0, 'screen' );
				break;
			case 'Droid Sans':
				wp_enqueue_style( 'font-droid-sans', 'http://fonts.googleapis.com/css?family=Droid+Sans', false, 1.0, 'screen' );
				break;
		}

	} elseif ( hybrid_get_setting( 'origin_font_family' ) ) {

		switch ( hybrid_get_setting( 'origin_font_family' ) ) {			
			case 'Bitter':
				wp_enqueue_style( 'font-bitter', 'http://fonts.googleapis.com/css?family=Bitter', false, 1.0, 'screen' );
				break;
			case 'Droid Serif':
				wp_enqueue_style( 'font-droid-serif', 'http://fonts.googleapis.com/css?family=Droid+Serif', false, 1.0, 'screen' );
				break;
			case 'Istok Web':
				wp_enqueue_style( 'font-istok-web', 'http://fonts.googleapis.com/css?family=Istok+Web', false, 1.0, 'screen' );
				break;
			case 'Droid Sans':
				wp_enqueue_style( 'font-droid-sans', 'http://fonts.googleapis.com/css?family=Droid+Sans', false, 1.0, 'screen' );
				break;
		}

	} else {	
		wp_enqueue_style( 'font-bitter', 'http://fonts.googleapis.com/css?family=Bitter', false, 1.0, 'screen' );
	}
}

?>