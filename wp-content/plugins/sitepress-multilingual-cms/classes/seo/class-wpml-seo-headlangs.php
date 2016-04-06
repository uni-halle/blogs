<?php

class WPML_SEO_HeadLangs {
	private $sitepress;

	/**
	 * WPML_SEO_HeadLangs constructor.
	 *
	 * @param SitePress $sitepress
	 */
	public function __construct( &$sitepress ) {
		$this->sitepress = &$sitepress;
	}

	private function get_seo_settings() {
		$seo_settings = $this->sitepress->get_setting( 'seo', array() );
		if ( ! array_key_exists( 'head_langs', $seo_settings ) ) {
			$seo_settings['head_langs'] = 1;
		}
		if ( ! array_key_exists( 'head_langs_priority', $seo_settings ) ) {
			$seo_settings['head_langs_priority'] = 1;
		}

		return $seo_settings;
	}

	public function init_hooks() {
		if ( $this->sitepress->get_wp_api()->is_front_end() ) {
			$seo_settings = $this->get_seo_settings();
			$head_langs = $seo_settings['head_langs'];
			if ( $head_langs ) {
				$priority = $seo_settings['head_langs_priority'];
				add_action( 'wp_head', array( $this, 'head_langs' ), $priority );
			}
		}
	}

	function head_langs() {
		$languages = $this->sitepress->get_ls_languages( array( 'skip_missing' => true ) );
		// If there are translations and is not paged content...

		//Renders head alternate links only on certain conditions
		$the_post = get_post();
		$the_id   = $the_post ? $the_post->ID : false;
		$is_valid = count( $languages ) > 1 && ! is_paged() && ( ( ( is_single() || is_page() ) && $the_id && get_post_status( $the_id ) == 'publish' ) || ( is_home() || is_front_page() || is_archive() ) );

		if ( $is_valid ) {
			foreach ( $languages as $code => $lang ) {
				$alternate_hreflang = apply_filters( 'wpml_alternate_hreflang', $lang['url'], $code );
				printf( '<link rel="alternate" hreflang="%s" href="%s" />' . PHP_EOL, $this->sitepress->get_language_tag( $code ), str_replace( '&amp;', '&', $alternate_hreflang ) );
			}
		}
	}

	function render_menu() {

		$seo     = $this->get_seo_settings();
		$options = array();
		foreach ( array( 1, 10 ) as $priority ) {
			$label    = __( 'As early as possible', 'sitepress' );
			if ( $priority > 1 ) {
				$label = sprintf( __( 'Later in the head section (priority %d)', 'sitepress' ), $priority );
			}
			$options[ $priority ] = array(
				'selected' => ( $priority == $seo['head_langs_priority'] ),
				'label'    => $label,
			);
		}

		?>
		<div class="wpml-section wpml-section-seo-options" id="lang-sec-9-5">
			<div class="wpml-section-header">
				<h3><?php _e( 'SEO Options', 'sitepress' ) ?></h3>
			</div>
			<div class="wpml-section-content">
				<form id="icl_seo_options" name="icl_seo_options" action="">
					<?php wp_nonce_field( 'icl_seo_options_nonce', '_icl_nonce' ); ?>
					<p>
						<input type="checkbox" id="icl_seo_head_langs" name="icl_seo_head_langs" <?php if ( $seo['head_langs'] )
							echo 'checked="checked"' ?> value="1"/>
						<label for="icl_seo_head_langs"><?php _e( "Display alternative languages in the HEAD section.", 'sitepress' ); ?></label>
					</p>
					<p>
						<label for="wpml-seo-head-langs-priority"><?php echo __( 'Position of hreflang links', 'sitepress' ); ?></label>
						<select name="wpml_seo_head_langs_priority" id="wpml-seo-head-langs-priority" <?php if ( ! $seo['head_langs'] ) echo 'disabled="disabled"' ?>>
							<?php
							foreach ($options as $priority => $option ) {
								?>
								<option value="<?php echo $priority; ?>" <?php echo $option['selected'] ? 'selected="selected"' :''; ?>><?php echo $option['label']; ?></option>
								<?php
							}
							?>
						</select>
					</p>
					<p class="buttons-wrap">
						<span class="icl_ajx_response" id="icl_ajx_response_seo"></span>
						<input class="button button-primary" name="save" value="<?php _e( 'Save', 'sitepress' ) ?>" type="submit"/>
					</p>
				</form>
			</div>
		</div>
		<?php
	}

}