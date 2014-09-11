<?php
/**
 * Handles public functionality.
 */
class Wdgpo_PublicPages {
	var $data;
	var $codec;

	function Wdgpo_PublicPages () { $this->__construct(); }

	function __construct () {
		$this->data = new Wdgpo_Options;
		$this->codec = new Wdgpo_Codec;
	}

	/**
	 * Main entry point.
	 *
	 * @static
	 */
	function serve () {
		$me = new Wdgpo_PublicPages;
		$me->add_hooks();
	}

	function js_load_scripts () {
		$lang = $this->data->get_option('language');
		echo '<script type="text/javascript" src="https://apis.google.com/js/plusone.js">';
		if ($lang) {
			echo '{lang: "' . $lang . '"}';
		}
		echo '</script>';

		if (!$this->data->get_option('analytics_integration')) return;
		$category = $this->data->get_option('analytics_category');
		$category = $category ? esc_js($category) : 'Google +1';
		echo <<<EOGaq
<script type="text/javascript">
function wdgpo_plusone_click (el) {
	if (typeof window._gaq != "undefined") {
		 _gaq.push(['_trackEvent', '{$category}', el.state, document.title]);
	}
}
</script>
EOGaq;
	}

	function inject_plusone_buttons ($body) {
		if (
			(is_home() && !$this->data->get_option('front_page'))
			||
			(!is_home() && !is_singular())
		) return $body;
		$position = $this->data->get_option('position');
		if ('top' == $position || 'both' == $position) {
			$body = $this->codec->get_code('plusone') . ' ' . $body;
		}
		if ('bottom' == $position || 'both' == $position) {
			$body .= " " . $this->codec->get_code('plusone');
		}
		return $body;
	}
	
	function inject_author_links ($body) {
		global $post;
		$profile = get_user_meta($post->post_author, 'wdgpo_gplus', true);
		if (!$profile) return $body;
		
		return $body . ' ' . $this->codec->get_code('author');
	}

	function inject_gplus_page_id () {
		$page_id = $this->data->get_option('gplus_page_id');
		if (!$page_id) return false;
		echo '<link href="https://plus.google.com/' . $page_id . '/" rel="publisher" />';
	}

	function add_hooks () {
		$action = $this->data->get_option('footer_render') ? 'wp_footer' : 'wp_print_scripts';
		add_action($action, array($this, 'js_load_scripts'));

		// Automatic +1 buttons
		if ('manual' != $this->data->get_option('position')) {
			add_filter('the_content', array($this, 'inject_plusone_buttons'), 10);
		}
		if ($this->data->get_option('gplus_profile_fields') && $this->data->get_option('gplus_autorship_links')) {
			add_filter('the_content', array($this, 'inject_author_links'), 10);
		}

		// Google+ Page
		if ($this->data->get_option('gplus_page_id')) {
			add_action('wp_head', array($this, 'inject_gplus_page_id'));
		}

		$this->codec->register();
	}
}