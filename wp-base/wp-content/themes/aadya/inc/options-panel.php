<?php

/* 
 * Loads the Options Panel
 *
 * If you're loading from a child theme use stylesheet_directory
 * instead of template_directory
 */
 
if ( !function_exists( 'optionsframework_init' ) ) {
	define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/admin/' );
	require_once dirname( __FILE__ ) . '/admin/options-framework.php';	
}

/* 
 * This is an example of how to add custom scripts to the options panel.
 * This one shows/hides the an option when a checkbox is clicked.
 *
 * You can delete it if you not using that option
 */

add_action('optionsframework_custom_scripts', 'optionsframework_custom_scripts');

function optionsframework_custom_scripts() { ?>

<script type="text/javascript">
jQuery(document).ready(function() {

	jQuery('#showhide_row_titles').click(function() {
  		jQuery('#section-front_page_row_one_title').fadeToggle(400);
		jQuery('#section-front_page_row_two_title').fadeToggle(400);
		jQuery('#section-front_page_row_three_title').fadeToggle(400);
		jQuery('#section-front_page_row_four_title').fadeToggle(400);
	});
	
	if (jQuery('#showhide_row_titles:checked').val() !== undefined) {
		jQuery('#section-front_page_row_one_title').show();
		jQuery('#section-front_page_row_two_title').show();
		jQuery('#section-front_page_row_three_title').show();
		jQuery('#section-front_page_row_four_title').show();
	}
	
});
</script>
 
<?php
}

/* 
 * Loads the Options From Different Location as per themes requirement 
 * 
 */
function aadya_options_framework_location_override() {
	return array('/inc/options.php');
}
add_filter('options_framework_location','aadya_options_framework_location_override');

/* 
 * This is an example of how to override a default filter
 * for 'textarea' sanitization and $allowedposttags + embed and script.
 */
add_action('admin_init','aadya_optionscheck_change_santiziation', 100);
 
function aadya_optionscheck_change_santiziation() {
    remove_filter( 'of_sanitize_textarea', 'of_sanitize_textarea' );
    add_filter( 'of_sanitize_textarea', 'aadya_custom_sanitize_textarea' );
}
 
function aadya_custom_sanitize_textarea($input) {
    global $allowedposttags;
    $custom_allowedtags["embed"] = array(
      "src" => array(),
      "type" => array(),
      "allowfullscreen" => array(),
      "allowscriptaccess" => array(),
      "height" => array(),
          "width" => array()
      );
      $custom_allowedtags["script"] = array(
	  "src" => array(),
      "type" => array()
	  );
 
      $custom_allowedtags = array_merge($custom_allowedtags, $allowedposttags);
      $output = wp_kses( $input, $custom_allowedtags);
    return $output;
}

?>