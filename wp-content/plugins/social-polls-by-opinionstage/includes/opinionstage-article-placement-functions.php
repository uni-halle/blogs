<?php

// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

	class OpinionStageArticlePlacement {
		static function initialize() {
			add_filter($hook = 'the_content', array(__CLASS__, $hook));
		}
		// Adds the article placement shortcode to each post
		static function the_content($content) {
			global $post;
			$type = $post->post_type;
			if (is_front_page() && is_home()) {
				return $content;
			}
			if($type == "post") {
				$os_options = (array) get_option(OPINIONSTAGE_OPTIONS_KEY);
				if (!empty($os_options['article_placement_id']) && $os_options['article_placement_active'] == 'true' && !is_admin() ) {
					$shortcode = do_shortcode(
						sprintf(
							'[osplacement id="%s"]', 
							$os_options['article_placement_id']
						)
					);
					return $content . $shortcode;
				}  
			}
			return $content;
		}	
		
	}
	return OpinionStageArticlePlacement::initialize();
?>
