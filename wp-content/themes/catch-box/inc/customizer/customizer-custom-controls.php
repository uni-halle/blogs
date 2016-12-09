<?php
/**
* @package Catch Themes
* @subpackage Catch Box
* @since Catch Box 1.6.2
*/


//Custom control for important link
class CatchBox_Important_Links extends WP_Customize_Control {
    public $type = 'important-links';

    public function render_content() {
    	//Add Theme instruction, Support Forum, Changelog, Donate link, Review, Facebook, Twitter, Google+, Pinterest links
        $important_links = array(
						'theme_instructions' => array(
							'link'	=> esc_url( 'https://catchthemes.com/theme-instructions/catch-box/' ),
							'text' 	=> __( 'Theme Instructions', 'catch-box' ),
							),
						'support' => array(
							'link'	=> esc_url( 'https://catchthemes.com/support/' ),
							'text' 	=> __( 'Support', 'catch-box' ),
							),
						'changelog' => array(
							'link'	=> esc_url( 'https://catchthemes.com/changelogs/catch-box-theme/' ),
							'text' 	=> __( 'Changelog', 'catch-box' ),
							),
						'donate' => array(
							'link'	=> esc_url( 'https://catchthemes.com/donate/' ),
							'text' 	=> __( 'Donate Now', 'catch-box' ),
							),
						'review' => array(
							'link'	=> esc_url( 'https://wordpress.org/support/view/theme-reviews/catch-box' ),
							'text' 	=> __( 'Review', 'catch-box' ),
							),
						);
		foreach ( $important_links as $important_link) {
			echo '<p><a href="' . $important_link['link'] .'" >' . esc_attr( $important_link['text'] ) .' </a></p>';
		}
    }
}


/**
  * Custom control for checkbox
  * This class adds a custom-checkbox. The value is stored in the hidden field. This is due to the fact that
  * our theme previously stored 1 and 0 strings as checkbox values
  */
class CatchBox_Customize_Checkbox extends WP_Customize_Control {
	public $type = 'catchbox_custom_checkbox';

	public $name;

	public $descripton;

	public $settings;

	public $default;

	public function render_content() {
		$this->value();
		$this->default;
		?>
		<label>
	        <input type="checkbox" <?php checked( '1', $this->value() ); ?>  /> <?php echo esc_html ( $this->label ); ?>

	        <input type="hidden" <?php $this->link(); ?> value="1" />
        </label>
         <?php if ( !empty( $this->description ) ) : ?>
            <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
        <?php endif; ?>
    <?php }
}