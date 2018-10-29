<?php
/**
 * Add dynamic CSS codes to new WP Editor
 */
function graphene_blocks_dynamic_css(){
	global $pagenow, $graphene_settings;
	if ( $pagenow != 'post.php' ) return;

	$content_width = graphene_get_content_width() + 45;
	$wide_width = floor( $content_width * 1.25 );

	/* Basic editor style */
	$style = '
		body.gutenberg-editor-page .editor-post-title__block,
		body.gutenberg-editor-page .editor-default-block-appender,
		body.gutenberg-editor-page .editor-block-list__block {
		    max-width: ' . $content_width . 'px !important;
		}
		body.gutenberg-editor-page .editor-block-list__block[data-align="wide"] {
		    max-width: ' . $wide_width . 'px !important;
		}
		.edit-post-visual-editor .editor-block-list__block[data-align=full] {
			max-width: none !important;
		}
		.edit-post-layout__metaboxes:not(:empty) {
			margin-top: 0;
		}
	';


	/* Build custom style according to theme settings */
	$colours = array(
		'content_bg' 			=> '.edit-post-layout__content .edit-post-visual-editor {background-color: %s;}',
		'content_font_colour' 	=> '.editor-block-list__block, .editor-block-list__block p, .editor-rich-text h1, .editor-rich-text h2, .editor-rich-text h3, .editor-rich-text h4, .editor-rich-text h5, .editor-rich-text h6, blockquote p, .wp-block-quote p, .editor-block-list__block .wp-block-quote p, .lead {color: %s}',
		'title_font_colour' 	=> '.editor-post-title__block .editor-post-title__input {color: %s}',
		'link_colour_normal' 	=> '.editor-block-list__block-edit a {color: %s}',
		'link_colour_hover' 	=> '.editor-block-list__block-edit a:focus, .editor-block-list__block-edit a:hover {color: %s}',
	);
	$style .= graphene_build_style( $colours );

	$colours = array(
		'button_bg|button_label'=> '.btn, .Button, .colour-preview .button, input[type="submit"], button[type="submit"], #commentform #submit, .wpsc_buy_button, #back-to-top, .wp-block-button .wp-block-button__link:not(.has-background) {background: %1$s; color: %2$s}',
	);
	$style .= graphene_build_style( $colours );
	
	/* Print the styles */
	echo '<style type="text/css">' . "\n" . graphene_minify_css( apply_filters( 'graphene_editor_style', $style ) ) . "\n" . '</style>' . "\n";
}
add_action( 'admin_head', 'graphene_blocks_dynamic_css', 100 );