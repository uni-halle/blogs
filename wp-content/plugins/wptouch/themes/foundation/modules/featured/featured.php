<?php

add_action( 'wp_loaded', 'foundation_featured_setup' );
add_action( 'foundation_module_init_mobile', 'foundation_featured_init' );
add_action( 'wptouch_admin_page_render_wptouch-admin-theme-settings', 'foundation_featured_settings' );

define( 'FOUNDATION_FEATURED_MIN_NUM', 2 );

global $foundation_featured_args;
global $foundation_featured_posts;

function foundation_featured_init() {
	$settings = foundation_get_settings();
	if ( $settings->featured_enabled ) {

		wp_enqueue_script(
			'foundation_featured',
			foundation_get_base_module_url() . '/featured/swipe.js',
			false,
			md5( FOUNDATION_VERSION ),
			true
		);

		wp_enqueue_script(
			'foundation_featured_init',
			foundation_get_base_module_url() . '/featured/wptouch-swipe.js',
			'foundation_featured',
			md5( FOUNDATION_VERSION ),
			true
		);
	}
}

function foundation_featured_setup() {
	if ( !is_admin() ) {
		if ( function_exists( 'add_theme_support' ) ) {
			add_theme_support( 'post-thumbnails' );
			add_image_size( 'foundation-featured-image', 900, 9999, false );
		}

		global $foundation_featured_posts;
		$settings = foundation_get_settings();
		$args = foundation_featured_get_args();

		$slides = foundation_featured_get_slides();
			$slide_count = 0;
		if ( $slides->post_count > 0 ) {
			while ( $slides->have_posts() && $slide_count < $args[ 'num' ] ) {
				$slides->the_post();
				$image = foundation_featured_has_image();
				if ( apply_filters( 'wptouch_has_post_thumbnail', $image ) ) {
					$slide_count++;
					$foundation_featured_posts[] = get_the_ID();
				}
			}
		}
		add_filter( 'parse_query', 'foundation_featured_modify_query' );
	}
}

function foundation_featured_config( $args ) {
	global $foundation_featured_args;

	$foundation_featured_args = $args;
}

function foundation_featured_modify_query( $query ) {
	if ( $query->is_main_query()  && !is_admin() && wptouch_is_showing_mobile_theme_on_mobile_device() ) {
		$settings = foundation_get_settings();

		if ( $settings->featured_filter_posts ) {
			return;
		}

		$should_be_ignored = apply_filters(
			'foundation_featured_should_modify_query',
			$query->is_single || $query->is_page || $query->is_feed || $query->is_search || $query->is_archive || $query->is_category,
			$query
		);

		if ( $should_be_ignored ) {
			return;
		}

		global $foundation_featured_posts;

		if ( count( $foundation_featured_posts ) < FOUNDATION_FEATURED_MIN_NUM ) {
			return $query;
		}

		$post_array = array();

		foreach( $foundation_featured_posts as $post_id ) {
			$post_array[] = '-' . $post_id;
		}

		$query->query_vars[ 'post__not_in']  = $post_array;

		return $query;
	}
}

function foundation_featured_get_args() {
	$settings = foundation_get_settings();
	$max_posts = $settings->featured_max_number_of_posts;

	global $foundation_featured_args;

	$defaults = array(
		'type' => 'post',
		'num' => $max_posts,
		'show_dots' => true,		// might not be needed
		'before' => '',
		'after' => '',
		'max_search' => 20,
		'ignore_sticky_posts' => 1
	);
	// Parse defaults into arguments
	return wp_parse_args( $foundation_featured_args, $defaults );
}

function foundation_featured_get_slides() {
	global $post;

	$settings = foundation_get_settings();

	$foundation_featured_posts = array();
	$foundation_featured_data = array();

	$args = foundation_featured_get_args();

	$new_posts = false;
	switch( $settings->featured_type ) {
		case 'tag':
			$new_posts = new WP_Query( array( 'ignore_sticky_posts' => 1, 'posts_per_page' => $args[ 'max_search' ], 'tag' => $settings->featured_tag ) );
			break;
		case 'category':
			$new_posts = new WP_Query( array( 'ignore_sticky_posts' => 1, 'posts_per_page' => $args[ 'max_search' ], 'category_name' => $settings->featured_category ) );
			break;
		case 'posts':
			if ( function_exists( 'wptouch_custom_posts_add_to_search' ) ) {
				$post_types = wptouch_custom_posts_add_to_search( array( 'post', 'page' ) );
			} else {
				$post_types = array( 'post', 'page' );
			}
			$post_ids = explode( ',', str_replace( ' ', '', $settings->featured_post_ids ) );
			if ( is_array( $post_ids ) && count( $post_ids ) ) {
				$new_posts = new WP_Query( array( 'post__in'  => $post_ids, 'posts_per_page' => $args[ 'max_search' ], 'post_type' => $post_types, 'orderby' => 'post__in' ) );
			}
			break;
		case 'post_type':
			$new_posts = new WP_Query( 'post_type=' . $settings->featured_post_type . '&posts_per_page=' . $args[ 'max_search' ] );
			break;
		case 'latest':
		default:
			break;
	}

	if ( !$new_posts ) {
		$new_posts = new WP_Query( 'posts_per_page=' . $args[ 'max_search' ] );
	}

	return $new_posts;
}

function foundation_featured_has_image( $post = false ) {
	if ( !$post ) {
		global $post;
	}

	$settings = foundation_get_settings();

	$image = get_the_post_thumbnail( $post->ID, 'foundation-featured-image' );

	if ( $image ) {
		return true;
	} else {
		return false;
	}
}

function foundation_featured_get_image( $post = false ) {
	if ( !$post ) {
		global $post;
	}

	$image = get_the_post_thumbnail( $post->ID, 'foundation-featured-image' );

    if ( preg_match( '#src=\"(.*)\"#iU', $image, $matches ) ) {
      $image = $matches[1];

      $our_size = sprintf( "%d", WPTOUCH_FEATURED_SIZE );
      if ( strpos( $image, $our_size ) === false ) {
        // It's not our image, so just use the WP medium size
        $image = get_the_post_thumbnail( $post->ID, 'large' );
        if ( preg_match( '#src=\"(.*)\"#iU', $image, $matches ) ) {
          $image = $matches[1];
        }
      }
    }

    return apply_filters( 'wptouch_get_post_thumbnail', $image );
}

function featured_should_show_slider() {
	$settings = foundation_get_settings();
	$should_show = ( ( is_home() || is_front_page() ) && $settings->featured_enabled );

	return apply_filters( 'foundation_featured_show', $should_show );
}

function foundation_featured_get_slider_classes() {
	$settings = foundation_get_settings();

	$featured_classes = array( 'swipe' );

	if ( $settings->featured_grayscale ) {
		$featured_classes[] = 'grayscale';
	}

	if ( $settings->featured_autoslide ) {
		$featured_classes[] = 'slide';
	}

	if ( $settings->featured_continuous ) {
		$featured_classes[] = 'continuous';
	}

	switch( $settings->featured_speed ) {
		case 'slow':
			$featured_classes[] = 'slow';
			break;
		case 'fast':
			$featured_classes[] = 'fast';
			break;
	}

	return $featured_classes;
}

function foundation_featured_slider( $manual = false, $manual_html = false ) {
	global $foundation_featured_posts;
	$args = foundation_featured_get_args();

	if ( $manual == false ) {

		if ( function_exists( 'wptouch_custom_posts_add_to_search' ) ) {
			$post_types = wptouch_custom_posts_add_to_search( array( 'post', 'page' ) );
		} else {
			$post_types = array( 'post', 'page' );
		}

		$slides = new WP_Query( array( 'ignore_sticky_posts' => 1, 'post__in' => $foundation_featured_posts, 'post_type' => $post_types ) );

		if ( $slides->post_count > 0 ) {
			echo $args['before'];
			echo "<div id='slider' class='" . implode( ' ', foundation_featured_get_slider_classes() ) . "'>\n";
			echo "<div class='swipe-wrap'>\n";
			while ( $slides->have_posts() ) {
				$slides->the_post();
				$image = foundation_featured_has_image();
				if ( apply_filters( 'wptouch_has_post_thumbnail', $image ) ) {
					get_template_part( 'featured-slider' );
				}
			}
			echo "</div>\n";
			echo "</div>\n";
			echo $args['after'];
		}

	} else {
		// Private for now, we'll improve manual mode for customer use in 3.2
		echo $args['before'];

		echo "<div id='slider' class='" . implode( ' ', foundation_featured_get_slider_classes() ) . "'>\n";
		echo "<div class='swipe-wrap'>\n";

		echo $manual_html;

		echo "</div>\n";
		echo "</div>\n";
		echo $args['after'];
	}
	wp_reset_query();
}

function foundation_featured_settings( $page_options ) {
	$settings = foundation_get_settings();

	$posts_to_show_label = false;
	if ( defined( 'WPTOUCH_IS_FREE' ) ) {
		$posts_to_show_label = 'Posts to display';
	}

	$featured_settings = array(
		wptouch_add_pro_setting(
			'range',
			'featured_max_number_of_posts',
			__( 'Number of posts in slider', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0',
			array(
				'min' => 1,
				'max' => 10,
				'step' => 1
			)
		),
		wptouch_add_pro_setting(
			'checkbox',
			'featured_autoslide',
			__( 'Slide automatically ', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0'
		),
		wptouch_add_pro_setting(
			'checkbox',
			'featured_continuous',
			__( 'Slides repeat', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0'
		),
		wptouch_add_pro_setting(
			'checkbox',
			'featured_grayscale',
			__( 'Make images grayscale', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0'
		),
		wptouch_add_setting(
			'checkbox',
			'featured_filter_posts',
			__( 'Slider posts also show in listings', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0'
		),
		wptouch_add_pro_setting(
			'list',
			'featured_speed',
			__( 'Slide transition speed', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0',
			array(
				'slow' => __( 'Slow', 'wptouch-pro' ),
				'normal' => __( 'Normal', 'wptouch-pro' ),
				'fast' => __( 'Fast', 'wptouch-pro' )
			)
		),
		wptouch_add_setting(
			'list',
			'featured_type',
			$posts_to_show_label,
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0',
			array(
				'latest' => __( 'Show latest posts', 'wptouch-pro' ),
				'tag' => __( 'Show posts from a specific tag', 'wptouch-pro' ),
				'category' => __( 'Show posts from a specific category', 'wptouch-pro' ),
				'post_type' => __( 'Show posts from a specific post type', 'wptouch-pro' ),
				'posts' => __( 'Show only specific posts or pages', 'wptouch-pro' )
			)
		),
		wptouch_add_setting(
			'text',
			'featured_tag',
			__( 'Only this tag', 'wptouch-pro' ),
			__( 'Enter the tag/category slug name', 'wptouch-pro' ),
			WPTOUCH_SETTING_BASIC,
			'2.0'
		),
		wptouch_add_setting(
			'text',
			'featured_tag',
			__( 'Only this tag', 'wptouch-pro' ),
			__( 'Enter the tag/category slug name', 'wptouch-pro' ),
			WPTOUCH_SETTING_BASIC,
			'2.0',
			false //foundation_get_tag_list()
		),
		wptouch_add_setting(
			'text',
			'featured_category',
			__( 'Only this category', 'wptouch-pro' ),
			__( 'Enter the tag/category slug name', 'wptouch-pro' ),
			WPTOUCH_SETTING_BASIC,
			'2.0',
			false //foundation_get_category_list()
		),
		wptouch_add_setting(
			'text',
			'featured_post_ids',
			__( 'Comma-separated list of post/page IDs', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0'
		)
	);

	if ( function_exists( 'wptouch_custom_posts_get_list' ) ) {
		$featured_settings[] = wptouch_add_pro_setting(
			'list',
			'featured_post_type',
			__( 'Only this post type', 'wptouch-pro' ),
			false,
			WPTOUCH_SETTING_BASIC,
			'2.0',
			array_merge( array( 'Select Post Type' ), wptouch_custom_posts_get_list() )
		);
	}

	wptouch_add_page_section(
		FOUNDATION_PAGE_GENERAL,
		__( 'Featured Slider', 'wptouch-pro' ),
		'foundation-featured-settings',
		array_merge(
			array(
				wptouch_add_setting(
					'checkbox',
					'featured_enabled',
					__( 'Enable featured slider', 'wptouch-pro' ),
					__( 'Requires at least 2 entries to contain featured images', 'wptouch-pro' ),
					WPTOUCH_SETTING_BASIC,
					'2.0'
				),
			),
			$featured_settings
		),
		$page_options,
		FOUNDATION_SETTING_DOMAIN,
		true,
		false,
		30
	);

	return $page_options;
}