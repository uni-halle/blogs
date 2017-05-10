<?php

/**
 * Creates a fullscreen chapter heading
 *
 * @since    1.0.0
 */
if ( ! function_exists( 'aesop_chapter_shortcode' ) ) {

	function aesop_chapter_shortcode( $atts ) {
		
		
		$defaults = array(
			'title'  	=> '',
			'subtitle'  => '',
			'bgtype'  	=> 'img',
			'img'   	=> '',
			'alternate_img'   	=> '',
			'full'  	=> '',
			'bgcolor'   => '',
			'minheight' => '260px',
			'maxheight' => '100%',
			'fixed_height' => '',
			'force_fullwidth' => 'off',
			'overlay_content'   => '',
			'revealfx'  => '',
			'video_autoplay' => 'on',
			'overlay_revealfx'          => ''
		);

		$atts = apply_filters( 'aesop_chapter_defaults', shortcode_atts( $defaults, $atts ) );

		// let this be used multiple times
		static $instance = 0;
		$instance++;
		$unique = sprintf( '%s-%s', get_the_ID(), $instance );

		ob_start();

		$inline_styles   = 'background-size:cover;background-position:center center;';
		$styles    = apply_filters( 'aesop_chapter_img_styles_'.esc_attr( $unique ), esc_attr( $inline_styles ) );
		$is_video = 'video' == $atts['bgtype'];
		if (wp_is_mobile() && $is_video) {
			$is_video = false;
			if (!empty($atts['alternate_img'])) {
			    $atts['bgtype'] = 'img';
			    $atts['img'] = $atts['alternate_img'];
			} else {
				$atts['bgtype'] = 'color';
			}
		}
		
		

		$vid_atts = null;
		if ($is_video) {
			$vid_atts = aesop_video_url_parse($atts['img']);
		}
		if ('img' == $atts['bgtype'] && $atts['img']) {
			$img_style     =  sprintf( 'style="background:url(\'%s\');%s min-height:%s;max-height:%s;"', esc_url( $atts['img'] ), $styles, $atts['minheight'],$atts['maxheight'] );		
		} else {
			$img_style     =  'style="height:auto;min-height:'.$atts['minheight'].';max-height:'.$atts['maxheight'].';"';
			if ('color' == $atts['bgtype'] && $atts['bgcolor']) {
				$img_style = 'style="min-height:'.$atts['minheight'].';max-height:'.$atts['maxheight'].';background-color: '.$atts['bgcolor'].';"';
				$atts['full'] ='off';
			} 
		}
		$img_style_class  = 'img' == $atts['bgtype'] && $atts['img'] ? 'has-chapter-image' : 'no-chapter-image';
		

		$video_chapter_class = $is_video ? 'aesop-video-chapter' : null;

		$full_class = 'on' == $atts['full'] && !$is_video ? 'aesop-chapter-full' : false;
		

		do_action( 'aesop_chapter_before', $atts, $unique ); // action

?>
			<div id="chapter-unique-<?php echo $unique;?>" <?php echo aesop_component_data_atts( 'chapter', $unique, $atts );?> class="aesop-article-chapter-wrap default-cover <?php echo $video_chapter_class;?> aesop-component <?php echo $img_style_class;?> <?php echo $full_class;?> " 
			    <?php echo aesop_revealfx_set($atts) ? 'style="visibility:hidden;"': null ?> 
			    data-title="<?php echo esc_attr( $atts['title'] );?>"
			>

				<?php do_action( 'aesop_chapter_inside_top', $atts, $unique ); // action ?>

				<div class="aesop-article-chapter clearfix" <?php echo $img_style;?> >

				    <?php if (empty($atts['overlay_content'])) { ?>
					<h2 class="aesop-cover-title" itemprop="title">
						<span><?php echo esc_html( $atts['title'] );?></span>

						<?php if ( $atts['subtitle'] ) { ?>
							<small><?php echo esc_html( $atts['subtitle'] );?></small>
						<?php } ?>
					</h2>
					<?php } ?>

					<?php if ( $is_video)  { ?>
					<div class="video-container">
						<?php 
						$autoplay ='autoplay="on"';
                        switch ($atts['video_autoplay']) {
							case 'off':
								$autoplay ='autoplay="off"';
								break;
							case 'play_scroll':
								$autoplay ='viewstart="on" viewend="on"';
								break;
						}
                        						
						$vidcode = do_shortcode( '[aesop_video src="'.$vid_atts['type'].'" id="'.$vid_atts['id'].'"  hosted="'.$atts['img'].'" loop="on" '.$autoplay.' disable_for_mobile="off"]' );  
						//echo preg_replace('/<div (id=".*?").*>/i','<div $1>', $vidcode);
						echo $vidcode;
						
						?>
					</div>
					<?php } ?>

				</div>

				<div class="aesop-chapter-overlay-content">
					<?php echo $atts['overlay_content']; ?>
				</div>
				<?php do_action( 'aesop_chapter_inside_bottom', $atts, $unique ); // action ?>

			</div>
		<?php

		do_action( 'aesop_chapter_after', $atts, $unique ); // action

		return ob_get_clean();
	}
}//end if

if ( ! function_exists( 'aesop_chapter_heading_loader' ) ) {

	add_action( 'wp', 'aesop_chapter_heading_loader', 11 );
	function aesop_chapter_heading_loader() {

		global $post;

		$default_location  = is_single() || is_page();
		$location    = apply_filters( 'aesop_chapter_component_appears', $default_location );

		if ( isset( $post->post_content ) && ( $location ) && has_shortcode( $post->post_content, 'aesop_chapter' ) ) {

			new AesopChapterHeadingComponent;

		}
	}

}

// @TODO - this needs to be moved to theme level because not all will use the offset and setup here
class AesopChapterHeadingComponent {

	public function __construct() {
		add_action( 'wp_footer', array( $this, 'aesop_chapter_loader' ), 21 );
	}

	public function aesop_chapter_loader() {


		// allow theme developers to determine the offset amount
		$chapterOffset = apply_filters( 'aesop_chapter_scroll_offset', 0 );
		
		$top = apply_filters( 'aesop_chapter_top_text', 'Top' );

		// filterable content class
		$postClass = get_post_class(); 
		if (in_array ( 'aesop-entry-content', $postClass ) )
		{
		   $contentClass = '.aesop-entry-content';
		} else {
		   $contentClass = apply_filters( 'aesop_chapter_scroll_container', '.entry-content' );
		}
/**/
		// filterabl content header class
		$contentHeaderClass = apply_filters( 'aesop_chapter_scroll_nav', '.aesop-entry-header' );
?>
			<!-- Chapter Loader -->
			<script>
				jQuery(document).ready(function(){
					contentClass = '<?php echo esc_attr( $contentClass );?>';
					if (jQuery(contentClass).length==0) {
						contentClass = '.aesop-entry-content';
					} 
					
					jQuery(contentClass).scrollNav({
						
						
					    sections: '.aesop-article-chapter-wrap',
					    arrowKeys: true,
					    insertTarget: '<?php echo esc_attr( $contentHeaderClass );?>',
					    insertLocation: 'appendTo',
					    showTopLink: <?php echo !empty($top) ? 'true' : 'false';?>,
					    showHeadline: false,
						topLinkText: '<?php echo esc_attr( $top );?>',
					    scrollOffset: <?php echo absint( $chapterOffset );?>,
					    onRender: function() {
					       	jQuery('.scroll-nav__section').each(function(){		
					       		var id = jQuery(this).attr('id');
					       		var title = jQuery(this).find('.aesop-article-chapter-wrap').attr('data-title');

					       		jQuery('.scroll-nav__link').each(function(){
					       			var match = jQuery(this).attr('href');

					       			if ( match == '#'+id ) {
					       				jQuery(this).text(title);
					       			}
					       		});

					       	});
					    }
					});

					var coverSizer = function(){
						jQuery('.aesop-chapter-full .aesop-article-chapter').css({'height':(jQuery(window).height())+'px'});
					}
					coverSizer();
				    jQuery(window).resize(function(){
	    				coverSizer();
					});

				});
			</script>

		<?php

		echo self::aesop_chapter_menu();

	}

	/**
	 * Draws the off canvas menu and close button
	 *
	 * @since 1.3.2
	 */
	public function aesop_chapter_menu() {

		$out = '<a id="aesop-toggle-chapter-menu" class="aesop-toggle-chapter-menu" href="#aesop-chapter-menu"><i class="dashicons dashicons-tag aesop-close-chapter-menu"></i></a>';
		$out .= '<div id="aesop-chapter-menu" class="aesop-chapter-menu">
					<i class="dashicons dashicons-no-alt aesop-close-chapter-menu"></i>
					<div class="aesop-chapter-menu--inner aesop-entry-header">
					</div>
				</div>';

		$return = apply_filters( 'aesop_chapter_menu_output', $out );

		return $return;
	}
}
