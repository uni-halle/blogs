<?php
if ( ! class_exists( 'FusionSC_Global' ) ) {
	/**
	 * Shortcode class.
	 *
	 * @package fusion-builder
	 * @since 1.2.2
	 */
	class FusionSC_Global extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.2.2
		 * @var array
		 */
		protected $args;

		/**
		 * An array of global elements in content
		 *
		 * @access private
		 * @since 1.0
		 * @var int
		 */
		private $global_elements = array();

		/**
		 * Constructor.
		 *
		 * @access public
		 * @since 1.2.2
		 */
		public function __construct() {

			parent::__construct();

			add_shortcode( 'fusion_global', array( $this, 'render' ) );

			add_filter( 'content_edit_pre', array( $this, 'add_global_elements' ), 10, 2 );
			add_filter( 'content_save_pre', array( $this, 'update_global_elements' ), 10, 1 );
		}

		/**
		 * Render the shortcode
		 *
		 * @access public
		 * @since 1.2.2
		 * @param  array  $args    Shortcode parameters.
		 * @param  string $content Content between shortcode.
		 * @return string          HTML output.
		 */
		public function render( $args, $content = '' ) {

			$defaults = FusionBuilder::set_shortcode_defaults(
				array(
					'id'    => '',
				), $args
			);

			extract( $defaults );
			$this->args = $defaults;

			// Check if ID is valid.
			if ( is_numeric( $this->args['id'] ) ) {
				// Get post contents.
				$post = get_post( $this->args['id'] );
				// Check if post exists.
				if ( ! is_null( $post ) ) {
					// Return contents.
					return do_shortcode( $post->post_content );
				}
			}
		}

		/**
		 * Filter and replace global element shortcode in content on editor load.
		 * replaces [fusion_global id="10"] with content.
		 *
		 * @since 1.2.2
		 * @param string $content Content of the current post/page.
		 * @param int    $post_id     ID of the current post/page.
		 * @return string
		 */
		function add_global_elements( $content, $post_id ) {
			 return $this->recursively_add_global_elements( $content, '' );
		}

		/**
		 * Recursively travese through content and add global elements to content.
		 *
		 * @since 1.2.2
		 * @param string $content       Content of the current post/page.
		 * @param string $base_content  Base content for recursive method.
		 * @return string
		 */
		function recursively_add_global_elements( $content, $base_content = '' ) {
			// early return if not global.
			$position = strpos( $content, 'fusion_global' );

			if ( false === $position ) {
				return $content;
			}

			$pattern = '\[(\[?)(fusion_global)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';

			// Extract short-codes from content.
			if ( preg_match_all( '/' . $pattern . '/s', $content, $matches ) && array_key_exists( 2, $matches ) && in_array( 'fusion_global', $matches[2] ) ) {
				// Loop through matches.
				foreach ( $matches[2] as $key => $value ) {
					if ( 'fusion_global' === $value ) {
						$result = shortcode_parse_atts( $matches[3][ $key ] );
						// Get relative global element CPT and replace in content.
						if ( isset( $result['id'] ) ) {
							$post = get_post( $result['id'] );

							if ( ! is_null( $post ) ) {
								$position           = strpos( $post->post_content, ']' );
								$post->post_content = substr_replace( $post->post_content, ' fusion_global="' . $result['id'] . '"]', $position, 1 );
								if ( ! empty( $base_content ) ) {
									$base_content = str_replace( $matches[0][ $key ], $post->post_content, $base_content );
								} else {
									$base_content = str_replace( $matches[0][ $key ], $post->post_content, $content );
								}
								$base_content   = $this->recursively_add_global_elements( $post->post_content, $base_content );
							} else {
								if ( ! empty( $base_content ) ) {
									$base_content = str_replace( $matches[0][ $key ], '', $base_content );
								} else {
									$base_content = str_replace( $matches[0][ $key ], '', $content );
								}
							}
						}
					}
				}
			}

			return ( 0 < strlen( $base_content ) ? $base_content : $content );
		}

		/**
		 * Filter and replace global elements with shortocode in content.
		 * replaces fusion_global="10" with short-code.
		 *
		 * @since 1.2.2
		 * @param string $content Content of the current post/page.
		 * @return string
		 */
		function update_global_elements( $content ) {
			// Early return if not global.
			$position = strpos( $content, 'fusion_global' );

			if ( false === $position ) {
				return $content;
			}

			// Unhook this method so it doesn't loop infinitely.
			remove_filter( 'content_save_pre', array( $this, 'update_global_elements' ), 10, 1 );

			$shortcodes = array();
			$shortcodes = $this->recurvisely_extract_shortcodes_from_content( $shortcodes, $content );

			$this->recursively_extract_globals_from_shortcodes( $shortcodes );

			$count      = count( $this->global_elements );
			$duplicates = array();

			// Remove duplicate globals as we are handling theme in JS.
			for ( $j = 0; $j < $count; $j++ ) {
				for ( $i = 0; $i < $count; $i++ ) {
					// Do not compare with itself.
					if ( $j !== $i ) {
						// If IDs are same.
						if ( isset( $this->global_elements[ $i ]['id'] ) && $this->global_elements[ $i ]['id'] === $this->global_elements[ $j ]['id'] ) {
							$duplicates[] = $this->global_elements[ $i ]['id'];
						}
					}
				}
			}

			// Loop through and update global elements templates.
			for ( $x = 0; $x < $count; $x++ ) {
				$to_replace = '[fusion_global id="' . $this->global_elements[ $x ]['id'] . '"]';
				// search and replace in nested elements.
				for ( $i = 0; $i < $count; $i++ ) {
					if ( false !== strpos( $this->global_elements[ $i ]['content'], $this->global_elements[ $x ]['content'] ) && $this->global_elements[ $i ]['content'] !== $this->global_elements[ $x ]['content'] ) {
						$this->global_elements[ $i ]['content'] = str_replace( $this->global_elements[ $x ]['content'], $to_replace , $this->global_elements[ $i ]['content'] );
					}
				}
				// Remove fusion_global from short-code attributes.
				$post_content = str_replace( addslashes( 'fusion_global="' . $this->global_elements[ $x ]['id'] . '"' ), '',$this->global_elements[ $x ]['content'] );
				$post = array(
					'ID'           => $this->global_elements[ $x ]['id'],
					'post_content' => $post_content,
				);
				if ( ! in_array( $this->global_elements[ $x ]['id'], $duplicates ) ) {
					// Update global element CPT.
					$post_id = wp_update_post( $post );
				}

				// Update original content.
				$content = str_replace( $this->global_elements[ $x ]['content'], $to_replace, $content );

			}

			// Re-hook this method.
			add_filter( 'content_save_pre', array( $this, 'update_global_elements' ), 10, 1 );

			return $content;
		}

		/**
		 * Find all global elements from short-codes array.
		 *
		 * @since 1.2.2
		 * @param array $shortcodes array of all shortcodes in content.
		 * @return string
		 */
		function recursively_extract_globals_from_shortcodes( $shortcodes ) {

			if ( is_array( $shortcodes ) && ! empty( $shortcodes ) ) {

				$count = count( $shortcodes );

				for ( $i = $count - 1; $i >= 0; $i-- ) {
					// if got child elements.
					if ( is_array( $shortcodes[ $i ]['child_content_arr'] ) ) {
						$this->recursively_extract_globals_from_shortcodes( $shortcodes[ $i ]['child_content_arr'] );
					}
					// if short-code has got attributes.
					if ( is_array( $shortcodes[ $i ]['atts'] ) ) {
						// Check whether fusion_global param exists in short-code.
						 $got_global = preg_grep( '/^fusion_global.*/' , $shortcodes[ $i ]['atts'] );
						if ( is_array( $got_global ) && 1 === count( $got_global ) ) {

							 $global_elements_data            = array();
							 $global_id                       = shortcode_parse_atts( $shortcodes[ $i ]['atts'][ key( $got_global ) ] );
							 $global_elements_data['id']      = $global_id['fusion_global'];
							 $global_elements_data['content'] = $shortcodes[ $i ]['content'];
							 $this->global_elements[]         = $global_elements_data;
						}
					}
				}
				return;
			}
			return;
		}

		/**
		 * Recursive function to extract all shortcodes from content.
		 *
		 * @since 1.2.2
		 * @param array  $arr_shortcodes data of all shortcodes in content.
		 * @param string $content Content string.
		 * @param bool   $is_child flag for child elements.
		 * @return array
		 */
		function recurvisely_extract_shortcodes_from_content( $arr_shortcodes, $content, $is_child = false ) {
			// get all registered short-code matches.
			$matches = $this->get_shortcode_matches( $content );
			if ( ! empty( $matches ) ) {
				list( $shortcodes, $d, $parents, $atts, $d, $contents ) = $matches;
				$child_arr_shortcodes                          = array();

				foreach ( $parents as $k => $parent ) {
					$shortcode_name                                         = $k;
					$sub_matches                                            = $this->get_shortcode_matches( $contents[ $k ] );
					// Check for child elements.
					$child_shortcodes                                       = $this->recurvisely_extract_shortcodes_from_content( $child_arr_shortcodes, $contents[ $k ], true );
					$arr_shortcodes[ $shortcode_name ]['name']              = $parents[ $k ];
					$arr_shortcodes[ $shortcode_name ]['content']           = $shortcodes[ $k ];
					$arr_shortcodes[ $shortcode_name ]['atts']              = shortcode_parse_atts( $atts[ $k ] );
					$arr_shortcodes[ $shortcode_name ]['child_content']     = $contents[ $k ];
					$arr_shortcodes[ $shortcode_name ]['child_content_arr'] = ! empty( $sub_matches ) && ! empty( $child_shortcodes ) ? $child_shortcodes : $contents[ $k ];
				}
			}

			return  array_filter( $arr_shortcodes );
		}

		/**
		 * Get all shortcode matches in content.
		 *
		 * @since 1.2.2
		 * @param string $content Content of the current post/page.
		 * @return array
		 */
		function get_shortcode_matches( $content ) {
			$pattern = get_shortcode_regex();
			preg_match_all( "/$pattern/s", $content, $matches );
			return $matches;
		}
	}
} // End if().

new FusionSC_Global();
