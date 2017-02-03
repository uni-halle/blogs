<?php
/**
 * Contains methods for customizing the theme customization screen.
 * @since Tempera 1.2.4
 */

$cryout_customizer = array(

'info_settings' => array(
	'support_link_faqs' => array(
		'label' => '',
		'default' => sprintf( '<a href="https://www.cryoutcreations.eu/wordpress-themes/' . _CRYOUT_THEME_NAME . '" target="_blank">%s</a>', __( 'Read the Docs', 'cryout' ) ),
		'desc' =>  '',
		'section' => 'cryoutspecial-about-theme',
	),
	'support_link_forum' => array(
		'label' => '',
		'default' => sprintf( '<a href="https://www.cryoutcreations.eu/forums/f/wordpress/' . cryout_sanitize_tn( _CRYOUT_THEME_NAME ) . '" target="_blank">%s</a>', __( 'Browse the Forum', 'cryout' ) ),
		'desc' => '',
		'section' => 'cryoutspecial-about-theme',
	),
	'premium_support_link' => array(
		'label' => '',
		'default' => sprintf( '<a href="https://www.cryoutcreations.eu/priority-support" target="_blank">%s</a>', __( 'Priority Support', 'cryout' ) ),
		'desc' => '',
		'section' => 'cryoutspecial-about-theme',
	),
	'rating_url' => array(
		'label' => '&nbsp;',
		'default' => sprintf( '<a href="https://wordpress.org/support/view/theme-reviews/'. cryout_sanitize_tn( _CRYOUT_THEME_NAME ).'#postform" target="_blank">%s</a>', sprintf( __( 'Rate %s on WordPress.org', 'cryout' ) , ucwords(_CRYOUT_THEME_NAME) ) ),
		'desc' => '',
		'section' => 'cryoutspecial-about-theme',
	),
),

'cryout_advanced_settings' => array(
	'default' => sprintf('<a href="themes.php?page=' . cryout_sanitize_tn( _CRYOUT_THEME_NAME ) . '-page">%s</a>', __('Manage Theme Settings', 'cryout') ),
	'label' => ucwords(_CRYOUT_THEME_NAME) . ' ' . __(  'Settings', 'cryout' ),
	'desc' => __("To configure the remaining 200+ theme options, access the dedicated settings page.<br><br><em>The settings page is only available when the theme is active. It cannot be previewed in the Customizer.</em>", 'cryout' ),
	'section' => 'cryout_advanced_settings',
	'priority' => 999,
), // advanced_settings

); // theme_customizer

///////// CUSTOM CUSTOMIZERS
function cryout_customizer_extras($wp_customize) {
	
	class Cryout_Customize_About_Control extends WP_Customize_Control {
			public $type = 'about';
			public function render_content() {
					if ( ! empty( $this->label ) ) { ?>
                        <span class="customize-control-title"><?php echo esc_attr( $this->label ) ?></span>
					<?php }
					if ( ! empty( $this->description ) ) { ?>
                        <span class="description customize-control-description cryout-nomove"><?php echo wp_kses_post( $this->description ) ?></span>
                    <?php } ?>
					<span class="customize-control-content customize-cryoutspecial-about-link"><?php echo wp_kses_post( $this->value() ) ?></span>
			<?php
			}
	} // class Cryout_Customize_About_Control

	class Cryout_Customize_Link_Control extends WP_Customize_Control {
			public $type = 'link';
			public function render_content() {
				if ( !empty( $this->description ) ) { ?>
					<li class="customize-section-description-container">
						<div class="description customize-section-description">
						    <?php echo $this->description; ?>
						</div>
					</li>
				<?php
				}
				echo '<a href="' . esc_url( $this->value() ) . '" target="_blank">' . esc_attr( $this->label ) .'</a>';
			}
	} // class Cryout_Customize_Link_Control

	class Cryout_Customize_Blank_Control extends WP_Customize_Control {
			public $type = 'blank';
			public function render_content() {
				echo '&nbsp;';
			}
	} // class Cryout_Customize_Link_Control

} // cryout_customizer_extras()

function cryout_customizer_sanitize_blank(){
	// dummy function that does nothing, since the sanitized add_section
	// calling it does not add any user-editable field
} // cryout_customizer_sanitize_blank()

class Cryout_Customizer {

   public static function register( $wp_customize ) {
		global $cryout_customizer;

		// add about theme panel and sections
		if (!empty($cryout_customizer['info_settings'])):
		$wp_customize->add_section( 'cryoutspecial-about-theme', array(
			'priority'       => 10,
			'title'          => sprintf( __( 'About %s', 'cryout' ), ucwords(_CRYOUT_THEME_NAME) ),
			'description'    => sprintf( __( '%1$s Theme by %2$s', 'cryout' ), ucwords(_CRYOUT_THEME_NAME), 'Cryout Creations' ),
		) );

		foreach ($cryout_customizer['info_settings'] as $iid => $info):
			$wp_customize->add_setting( $iid, array(
				'default'        => $info['default'],
				'capability'     => 'edit_theme_options',
				'sanitize_callback' => 'cryout_customizer_sanitize_blank'
			) );
			$wp_customize->add_control( new Cryout_Customize_About_Control( $wp_customize, $iid, array(
				'label'   	 => $info['label'],
				'description' => $info['desc'],
				'section' 	 => $info['section'],
				'default' 	 => $info['default'],
				'settings'   => $iid,
				'priority'   => 10,
			) ) );
		endforeach;
		////////// end about panel
		endif; //!empty

		// add settings page panel and section
		if (!empty($cryout_customizer['cryout_advanced_settings'])):
		$adv = $cryout_customizer['cryout_advanced_settings'];

		$wp_customize->add_section( $adv['section'], array(
			'title'          => $adv['label'],
			'description'    => '',
			'priority'       => $adv['priority'],
			//'panel'  => $opt['section'],
			) );

		$wp_customize->add_setting( $adv['section'], array(
			'default'        => $adv['default'],
			'capability'     => 'edit_theme_options',
			'sanitize_callback' => 'cryout_customizer_sanitize_blank'
		) );
		$wp_customize->add_control( new Cryout_Customize_About_Control( $wp_customize, $adv['section'], array(
			'label'   	 => '', //$adv['label'],
			'description' => $adv['desc'],
			'section' 	 => $adv['section'],
			'settings'   => $adv['section'],
			'priority'   => $adv['priority'],
		) ) );
		endif;
		// end settings panel

   } // register()

} // class Cryout_Customizer

// Setup the Theme Customizer settings and controls...
add_action( 'customize_register', 'cryout_customizer_extras' );
add_action( 'customize_register', array('Cryout_Customizer', 'register' ) );

	////////// external resources //////////
function cryout_customizer_enqueue_scripts() {
	wp_enqueue_style( 'cryout-customizer-css', get_template_directory_uri() . '/admin/css/customizer.css', array(), _CRYOUT_THEME_VERSION );
	//wp_enqueue_script( 'cryout-customizer-js', get_template_directory_uri() . '/admin/js/customizer.js', array( 'jquery' ), _CRYOUT_THEME_VERSION, true );
}
add_action('customize_controls_enqueue_scripts', 'cryout_customizer_enqueue_scripts');


function cryout_sanitize_tn($input){
	return preg_replace( '/[^a-z0-9-]/i', '-', $input );
}

function cryout_sanitize_tn_fn($input){
	return preg_replace( '/[^a-z0-9]/i', '_', $input );
}

// FIN