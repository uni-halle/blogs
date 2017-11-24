<?php
// block direct access to plugin PHP files:
defined( 'ABSPATH' ) or die();

function opinionstage_enqueue_shortcodes_assets() {
	opinionstage_register_javascript_asset('shortcodes', 'shortcodes.js', array('jquery'));

	opinionstage_enqueue_js_asset('shortcodes');
}

function opinionstage_poll_or_set_shortcode($atts) {
	if ( is_feed() ) {
		return __("Note: There is a poll embedded within this post, please visit the site to participate in this post's poll.", OPINIONSTAGE_TEXT_DOMAIN);
	} else {
		$shortcode_params = shortcode_atts(
			array('id' => 0, 'type' => 'poll', 'width' => ''),
			$atts,
			OPINIONSTAGE_POLL_SHORTCODE
		);

		$id = intval($shortcode_params['id']);
		$type = $shortcode_params['type'];
		$width = $shortcode_params['width'];

		return opinionstage_widget_placement( opinionstage_poll_or_set_embed_code_url($id, $type, $width) );
	}
}

function opinionstage_widget_shortcode($atts) {
	if ( is_feed() ) {
		return __("Note: There is a widget embedded within this post, please visit the site to participate in this post's widget.", OPINIONSTAGE_TEXT_DOMAIN);
	} else {
		$shortcode_params = shortcode_atts(
			array('path' => 0, 'comments' => 'true', 'sharing' => 'true', 'recommendations' => 'false', 'width' => ''),
			$atts,
			OPINIONSTAGE_WIDGET_SHORTCODE
		);

		$path = $shortcode_params['path'];
		$comments = $shortcode_params['comments'];
		$sharing = $shortcode_params['sharing'];
		$recommendations = $shortcode_params['recommendations'];
		$width = $shortcode_params['width'];

		return opinionstage_widget_placement( opinionstage_widget_embed_code_url($path, $comments, $sharing, $recommendations, $width) );
	}
}

function opinionstage_placement_shortcode($atts) {
	if ( !is_feed() ) {
		$shortcode_params = shortcode_atts(
			array('id' => 0),
			$atts,
			OPINIONSTAGE_PLACEMENT_SHORTCODE
		);

		$id = intval($shortcode_params['id']);

		return opinionstage_widget_placement( opinionstage_placement_embed_code_url($id) );
	}
}

function opinionstage_poll_or_set_embed_code_url($id, $type, $width) {
	if ( isset($id) && !empty($id) ) {
		if ($type == 'set') {
			$embed_code_url = OPINIONSTAGE_API_PATH."/sets/" . $id . "/code.json";
		} else {
			$embed_code_url = OPINIONSTAGE_API_PATH."/polls/" . $id . "/code.json?width=".$width;
		}

		if ( is_home() ) {
			$embed_code_url .= "?h=1";
		}

		return $embed_code_url;
	}
}

function opinionstage_widget_embed_code_url($path, $comments, $sharing, $recommendations, $width) {
	if ( isset($path) && !empty($path) ) {
		$embed_code_url = OPINIONSTAGE_API_PATH."/widgets" . $path . "/code.json?comments=".$comments."&sharing=".$sharing."&recommendations=".$recommendations."&width=".$width;
		return $embed_code_url;
	}
}

function opinionstage_placement_embed_code_url( $id ) {
	if ( isset($id) && !empty($id) ) {
		$embed_code_url = OPINIONSTAGE_API_PATH."/placements/" . $id . "/code.json";
		return $embed_code_url;
	}
}

function opinionstage_widget_placement( $url ) {
	ob_start();
?>
	<div data-opinionstage-embed-url="<?php echo $url ?>" style="display: none; visibility: hidden;"></div>
<?php
	return ob_get_clean();
}
?>
