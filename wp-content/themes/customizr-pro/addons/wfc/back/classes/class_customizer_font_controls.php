<?php
/**
* Add controls to the WP customizer
*
*
* @package      Customizr
* @subpackage   classes
* @since        1.0
* @author       Nicolas GUILLAUME <nicolas@presscustomizr.com>
* @copyright    Copyright (c) 2013, Nicolas GUILLAUME
* @link         http://presscustomizr.com/customizr
* @license      http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

class TC_font_controls extends WP_Customize_Control	{
  public $selector;
  public $title;
  public $customs;
  public $lang_domain;
  public $selector_settings;

  public function __construct( $manager, $id, $args = array() ) {
    $_opt_prefix              = TC_wfc::$instance -> plug_option_prefix;
  	$this -> customs 			    = get_option( "{$_opt_prefix}_customs_" . TC_wfc::$theme_name);
  	$this -> lang_domain 		  = TC_wfc::$instance -> plug_lang;
	  $this -> selector_settings 	= TC_wfc::$instance -> tc_get_selector_list();

	  parent::__construct( $manager, $id, $args );
	}

	function render_content() {

		$selector_settings 	= $this -> selector_settings;
		$customs 			= $this -> customs;
		$is_custom 			= isset( $customs[$this->selector] );
		$selector_title 	= ( isset($selector_settings[$this->selector]['title']) && false === $selector_settings[$this->selector]['title'] ) ? esc_html( $this->title) : $selector_settings[$this->selector]['title'];
		?>
		<ul class="tc-setting-title-wrapper <?php echo $is_custom ? 'is-custom' : '' ?>">
			<li>
				<?php
				printf('<h3 class="font-customizer-title tc-accordion-section-title"><a href="#" title="%1$s">%1$s</a></h3>',
					isset($selector_title) ? esc_html( $selector_title) : ''
				);
				printf('<div class="tc-reset-block">%1$s%2$s%3$s</div>',
					! $is_custom ? '' : sprintf('<span class="tc-open-button tc-open-single-remove button"><a class="tc-customizer-icon tc-remove-icon" href="#" title="%1$s"></a></span>',
							__('Remove this selector' , $this -> lang_domain )
					),
					! $is_custom ? '' : sprintf('<span class="tc-open-button tc-open-single-edit button"><a class="tc-customizer-icon tc-edit-icon" href="#" title="%1$s"></a></span>',
							__('Edit this selector' , $this -> lang_domain )
					),
					sprintf('<span class="tc-open-button tc-open-single-reset button not-changed"><a class="tc-customizer-icon tc-reset-icon" href="#" title="%1$s"></a></span>',
						__('Reset to default' , $this -> lang_domain )
					)
				);//end printf
				printf('<div class="message settings-updated"><p>%1$s</p></div>',
						__('Reset to default' , $this -> lang_domain )
					)
				?>
			</li>
			<li>
				<?php
				printf('<div class="tc-alert alert-reset"><p>%1$s <strong>%2$s</strong> %3$s.</p>%4$s</div>',
					__('Please confirm to reset the settings for' , $this -> lang_domain ),
					esc_html( $selector_title),
					__('to default' , $this -> lang_domain ),
					sprintf('<span class="tc-single-reset button">%1$s</span> <span class="tc-cancel button">%2$s</span>',
						__('Yes' , $this -> lang_domain ),
						__('No' , $this -> lang_domain )
					)//end sprintf
				);//end printf
				printf('<div class="tc-alert alert-remove"><p>%1$s <strong>%2$s</strong>.</p>%3$s</div>',
					__('Please confirm to remove this selector :' , $this -> lang_domain ),
					esc_html( $selector_title),
					sprintf('<span class="tc-single-remove button">%1$s</span> <span class="tc-cancel button">%2$s</span>',
						__('Yes' , $this -> lang_domain ),
						__('No' , $this -> lang_domain )
					)//end sprintf
				)//end printf
				?>
			</li>
		</ul><!-- tc-setting-title-wrapper -->

		<ul class="tc-accordion-section-content">
			<?php
			foreach ( TC_admin_font_customizer::$instance -> tc_control_tree as $section => $tc_settings ) {
				//Special case for icon : don't show the section if empty
				/*if ( isset($tc_settings['controls']['icon']) && false == $selector_settings[$this->selector]['icon'] )
						continue;*/

				printf('<li class="tc-accordion-subsection %1$s"><h3>%2$s</h3><ul>%3$s</ul></li> ',
					'tc-'.str_replace(' ', '-', strtolower ( $section )),
					$tc_settings['title'],
					$this -> tc_render_single_controls( $tc_settings['controls'] )
				);
			}
			?>
		</ul>

		<?php if (isset( $this->notice)) : ?>
			<span class="tc-notice"><?php echo esc_html( $this-> notice ) ?></span>
		<?php endif; ?>

		<input <?php $this->link() ?> type="hidden" class="font-customizer-hidden-input" value="<?php //json_encode($this -> value()) ?>">

		<?php
	}//end of function



	function tc_render_single_controls( $tc_settings ) {
		$html 						= '';
		$input 						= '';
		$section_class 				= 'tc-customizer-section';
		$setting_title_class 		= 'tc-font-setting-title customize-control-title';
		$selector_settings 			= $this -> selector_settings;

		if ( !isset($selector_settings[$this->selector]) )
			return;

		foreach ($tc_settings as $setting_type => $data) {
			//declares a variable to be used out of the switch statement
			$continue = false;

			//First checks if the current selector has this setting type
			if ( ! array_key_exists($setting_type , $selector_settings[$this->selector]) )
				continue;

			switch ($data[1][0]) {

				case 'color-picker-hex' :
					$input =  '<input class="color-picker-hex" type="text" maxlength="7" placeholder="Hex Value" />';
					break;

				case 'tc-number-input' :
					$input =  sprintf('<input class="tc-number-input small-text" data-setting-type="%1$s" type="number">',
							$setting_type
					);
					break;

				case 'tc-check-input' :
					if ( 'icon' == $setting_type && false == $selector_settings[$this->selector]['icon'] ) {
						$continue = true;
					}
					$input = '<input class="' . $data[1][0] . '" type="checkbox">';
				break;

				default :
					$input = '<input class="' . $data[1][0] . '" type="text">';
			}
			//if $continue has been set to true during the switch statement then go to next loop
			if ( true == $continue )
				continue;

			$html .= sprintf('<li class="%1$s %2$s">%3$s %4$s</li>',
						$section_class,
						'tc-select-'.$setting_type,
						sprintf('<span class="%1$s">%2$s</span>',
							$setting_title_class,
							$data[0]
						),
						$input
			);
		}//end foreach

		return $html;
	}



	public function enqueue() {
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style( 'wp-color-picker' );
	}


}//end of class