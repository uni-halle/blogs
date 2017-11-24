<?php 
/**
 * Retrieve the theme's user settings and default settings. Individual files can access
 * these setting via a global variable call, so database query is only
 * done once.
*/
function graphene_get_settings(){
	global $graphene_defaults;
	$graphene_settings = array_merge( $graphene_defaults, (array) get_option( 'graphene_settings', array() ) );

	return apply_filters( 'graphene_settings', $graphene_settings );
}


/**
 * Initialise settings
 *
 * @package Graphene
 * @since 2.0
 */
function graphene_init_settings(){
	require( GRAPHENE_ROOTDIR . '/admin/options-defaults.php' );
	global $graphene_defaults, $graphene_settings;
	$graphene_defaults = apply_filters( 'graphene_defaults', $graphene_defaults );
	$graphene_settings = graphene_get_settings();
}


/**
 * Include files required for the theme's options 
 */
include( GRAPHENE_ROOTDIR . '/admin/user.php' );
include( GRAPHENE_ROOTDIR . '/admin/custom-fields.php' );
include( GRAPHENE_ROOTDIR . '/admin/options.php' );
include( GRAPHENE_ROOTDIR . '/admin/customizer/controls.php' );
include( GRAPHENE_ROOTDIR . '/admin/customizer/customizer.php' );
include( GRAPHENE_ROOTDIR . '/admin/ajax-handler.php');
include( GRAPHENE_ROOTDIR . '/admin/wpml-helper.php' );
include( GRAPHENE_ROOTDIR . '/admin/welcome/welcome-screen.php' );

/* Include the feature pointer */
/* Disabled for now until a proper API has been implemented in WordPress core */
// include( GRAPHENE_ROOTDIR . '/admin/feature-pointers.php');

/** 
 * Adds the theme options page
*/
function graphene_options_init() {
	global $graphene_settings;
	
	$graphene_settings['hook_suffix'] = add_theme_page( __( 'Graphene Options', 'graphene' ), __( 'Graphene Options', 'graphene' ), 'edit_theme_options', 'graphene_options', 'graphene_options' );
	
	add_action( 'admin_print_styles-' . $graphene_settings['hook_suffix'], 'graphene_admin_options_style' );
	add_action( 'admin_print_scripts-' . $graphene_settings['hook_suffix'], 'graphene_admin_scripts' );
	
	do_action( 'graphene_options_init' );
}
add_action( 'admin_menu', 'graphene_options_init', 8 );


/**
 * Allow users with 'edit_theme_options' capability to be able to modify the theme's options
 */
function graphene_options_page_capability( $cap ){
	return apply_filters( 'graphene_options_page_capability', 'edit_theme_options' );
}
add_filter( 'option_page_capability_graphene_options', 'graphene_options_page_capability' );


/**
 * Enqueue style for admin page
*/
if ( ! function_exists( 'graphene_admin_options_style' ) ) :
	function graphene_admin_options_style() {
		wp_enqueue_style( 'font-awesome', GRAPHENE_ROOTURI . '/fonts/font-awesome/css/font-awesome.min.css' );
		wp_enqueue_style( 'graphene-admin-style', GRAPHENE_ROOTURI . '/admin/admin.css' );
		if ( is_rtl() ) wp_enqueue_style( 'graphene-admin-style-rtl', GRAPHENE_ROOTURI . '/admin/admin-rtl.css' );
	}
endif;


/**
 * Script required for the theme options page
 */
function graphene_admin_scripts() {
	wp_enqueue_script( 'graphene-admin', GRAPHENE_ROOTURI . '/admin/js/admin.js', array( 'jquery' ), '', false );
}


/**
 * Add a link to the theme's options page in the admin bar
*/
function graphene_wp_admin_bar_theme_options(){
	if ( ! current_user_can( 'edit_theme_options' ) ) return;
	global $wp_admin_bar;
	$wp_admin_bar->add_menu( array( 
								'parent' 	=> 'appearance',
								'id' 		=> 'graphene-options',
								'title' 	=> 'Graphene Options',
								'href' 		=> admin_url( 'themes.php?page=graphene_options' ) ) );
}
add_action( 'admin_bar_menu', 'graphene_wp_admin_bar_theme_options', 61 );


/**
 * Displays a graphic visualizer for template selection in the Edit Page screen
*/
function graphene_page_template_visualizer() {  
    global $graphene_settings, $post_id;
    $template_not_found = __( 'Template preview not found.', 'graphene' );    
    
	if ( ! get_post_meta( $post_id, '_wp_page_template', true ) ){
		$default_template = __( 'default', 'graphene' );
	} else {
		switch( $graphene_settings['column_mode']){
			case 'one_column':
				$default_template = 'template-onecolumn.php';
				break;
			case 'two_col_right':
				$default_template = 'template-twocolumnsright.php';
				break;
			case 'three_col_left':
				$default_template = 'template-threecolumnsleft.php';
				break;
			case 'three_col_right':
				$default_template = 'template-threecolumnsright.php';
				break;
			case 'three_col_center':
				$default_template = 'template-threecolumnscenter.php';
				break;
			default:
				$default_template = 'template-twocolumnsleft.php';
				break;
		}
	}
    
    
    $preview_img_path = GRAPHENE_ROOTURI . '/admin/images/';
    ?>
    <script type="text/javascript">
    //<![CDATA[
    jQuery(document).ready(function($){
        $( '#page_template' ).change(function(){
           update_page_template();           
        });
		$( '#page_template' ).after( '<p><img id="page_template_img" alt="" /></p>' );
        
        function update_page_template() {
            var preview_img = $( '#page_template' ).val().replace(/.php$/, '.png' );
			<?php if ( is_rtl() ) : ?>
				if ( preview_img.indexOf('left') > -1 ) preview_img = preview_img.replace('left','right');
				else if ( preview_img.indexOf('right') > -1 ) preview_img = preview_img.replace('right','left');
			<?php endif; ?>
            if (preview_img == 'default' ) $( '#page_template_img' ).removeAttr('src');
            $( '#page_template_img' ).attr( 'src', '<?php echo $preview_img_path ?>'+preview_img);
        }
        
        // if the template preview image is not found, hide the image not found and show text
        $( '#page_template_img' ).error(function(){
           $(this).hide();  
           $( 'span', $(this).parent() ).show();
        });
        // if the template preview image is found, show the image
        $( '#page_template_img' ).load(function(){
           $(this).show();     
           $( 'span', $(this).parent() ).hide();
        });
        
        // remove the default option (because the theme overrides the template
        $( '#page_template option[value="default"]' ).remove();
        // add the theme default item
        $( '#page_template option:first' ).before( $( '<option value="default"><?php _e( 'Theme default', 'graphene' ); ?></option>' ) );
        // select the default template if it isn't already selected
        if ( $( '#page_template option[selected="selected"]' ).length == 0){
            // $( '#page_template option[text="<?php echo $default_template; ?>"]' ).attr( 'selected', 'selected' );
            $( '#page_template option:contains("<?php _e( 'Theme default', 'graphene' ); ?>")' ).attr( 'selected', 'selected' );
        }
        
        update_page_template();
    });
    //]]>
    </script>
    <?php
}
add_action( 'edit_page_form', 'graphene_page_template_visualizer' ); // only works on pages 


/**
 * Add content width parameter to the WordPress editor
 */
function graphene_editor_width(){
	global $content_width, $graphene_settings;
	$content_width = graphene_get_content_width();
	?>
    <script type="text/javascript">
		jQuery(document).ready(function($) {
			setTimeout( function(){
				$('#content_ifr').contents().find('#tinymce').css( 'width', '<?php echo $content_width; ?>' );
			}, 2000 );
		});
	</script>
    <?php
}
add_action( 'after_wp_tiny_mce', 'graphene_editor_width' );


if ( ! function_exists( 'graphene_docs_link' ) ) :
/**
 * Display a link to the documentation page
 *
 * @package Graphene
 * @since 1.9.1
 */
function graphene_docs_link( $suffix = '' ){
	$docs_uri = 'http://docs.graphene-theme.com/' . $suffix;
	?>
    	<a href="<?php echo $docs_uri; ?>" class="graphene-docs-link" title="<?php esc_attr_e( 'Learn more about this feature set', 'graphene' ); ?>" target="_blank">
        	<img src="<?php echo GRAPHENE_ROOTURI; ?>/admin/images/ico-info.png" alt="<?php esc_attr_e( 'Documentation', 'graphene' ); ?>" width="16" height="16" />
        </a>
    <?php
}
endif;