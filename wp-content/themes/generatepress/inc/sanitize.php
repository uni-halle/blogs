<?php
/**
 * Sanitize integers
 * @since 1.0.8
 */
function generate_sanitize_integer( $input ) {
	return absint( $input );
}

/**
 * Sanitize checkbox values
 * @since 1.0.8
 */
function generate_sanitize_checkbox( $input ) {
	if ( $input ) {
		$output = '1';
	} else {
		$output = false;
	}
	return $output;
}

/**
 * Sanitize header layout
 * @since 1.0.8
 */
function generate_sanitize_header_layout( $input ) {
    $valid = array(
        'fluid-header' => __( 'Fluid / Full Width', 'generate' ),
		'contained-header' => __( 'Contained', 'generate' )
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return 'fluid-header';
    }
}

/**
 * Sanitize navigation layout
 * @since 1.0.8
 */
function generate_sanitize_nav_layout( $input ) {
    $valid = array(
        'fluid-nav' => __( 'Fluid / Full Width', 'generate' ),
		'contained-nav' => __( 'Contained', 'generate' )
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return 'fluid-nav';
    }
}

/**
 * Sanitize typography dropdown
 * @since 1.1.10
 */
function generate_sanitize_typography( $input ) 
{

	// Grab all of our fonts
	$fonts = ( get_transient('generate_all_google_fonts') ? get_transient('generate_all_google_fonts') : array() );
	
	// Loop through all of them and grab their names
	$font_names = array();
	foreach ( $fonts as $k => $fam ) {
		$font_names[] = $fam['name'];
	}
	
	// Get all non-Google font names
	$not_google = array(
		'inherit',
		'Arial, Helvetica, sans-serif',
		'Century Gothic',
		'Comic Sans MS',
		'Courier New',
		'Georgia, Times New Roman, Times, serif',
		'Helvetica',
		'Impact',
		'Lucida Console',
		'Lucida Sans Unicode',
		'Palatino Linotype',
		'Tahoma, Geneva, sans-serif',
		'Trebuchet MS, Helvetica, sans-serif',
		'Verdana, Geneva, sans-serif'
	);

	// Merge them both into one array
	$valid = array_merge( $font_names, $not_google );
	
	// Sanitize
    if ( in_array( $input, $valid ) ) {
        return $input;
    } else {
        return 'Open Sans';
    }
}

/**
 * Sanitize font weight
 * @since 1.1.10
 */
function generate_sanitize_font_weight( $input ) {

    $valid = array(
        'normal',
		'bold',
		'100',
		'200',
		'300',
		'400',
		'500',
		'600',
		'700',
		'800',
		'900'
    );
 
    if ( in_array( $input, $valid ) ) {
        return $input;
    } else {
        return 'normal';
    }
}

/**
 * Sanitize text transform
 * @since 1.1.10
 */
function generate_sanitize_text_transform( $input ) {

    $valid = array(
        'none',
		'capitalize',
		'uppercase',
		'lowercase'
    );
 
    if ( in_array( $input, $valid ) ) {
        return $input;
    } else {
        return 'none';
    }
}

/**
 * Sanitize navigation alignment
 * @since 1.1.1
 */
function generate_sanitize_alignment( $input ) {
    $valid = array(
        'left' => __( 'Left', 'generate' ),
		'center' => __( 'Center', 'generate' ),
		'right' => __( 'Right', 'generate' )
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return 'left';
    }
}

/**
 * Sanitize navigation alignment
 * @since 1.1.1
 */
function generate_sanitize_nav_search( $input ) {
    $valid = array(
        'enable' => __( 'Enabled', 'generate' ),
		'disable' => __( 'Disabled', 'generate' )
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return 'disable';
    }
}

/**
 * Sanitize navigation position
 * @since 1.0.8
 */
function generate_sanitize_nav_position( $input ) {
    $valid = array(
        'nav-below-header' => __( 'Below Header', 'generate' ),
		'nav-above-header' => __( 'Above Header', 'generate' ),
		'nav-float-right' => __( 'Float Right', 'generate' ),
		'nav-left-sidebar' => __( 'Left Sidebar', 'generate' ),
		'nav-right-sidebar' => __( 'Right Sidebar', 'generate' ),
		'' => __( 'No Navigation', 'generate' )
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

/**
 * Sanitize content layout
 * @since 1.0.8
 */
function generate_sanitize_content_layout( $input ) {
    $valid = array(
        'separate-containers' => __( 'Separate Containers', 'generate' ),
		'one-container' => __( 'One Container', 'generate' )
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return 'separate-containers';
    }
}

/**
 * Sanitize sidebar layout
 * @since 1.0.8
 */
function generate_sanitize_sidebar_layout( $input ) {
    $valid = array(
        'left-sidebar' => __( 'Sidebar / Content', 'generate' ),
		'right-sidebar' => __( 'Content / Sidebar', 'generate' ),
		'no-sidebar' => __( 'Content (no sidebars)', 'generate' ),
		'both-sidebars' => __( 'Sidebar / Content / Sidebar', 'generate' ),
		'both-left' => __( 'Sidebar / Sidebar / Content', 'generate' ),
		'both-right' => __( 'Content / Sidebar / Sidebar', 'generate' )
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return 'right-sidebar';
    }
}

/**
 * Sanitize footer layout
 * @since 1.0.8
 */
function generate_sanitize_footer_layout( $input ) {
    $valid = array(
        'fluid-footer' => __( 'Fluid / Full Width', 'generate' ),
		'contained-footer' => __( 'Contained', 'generate' )
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return 'fluid-footer';
    }
}

/**
 * Sanitize footer widgets
 * @since 1.0.8
 */
function generate_sanitize_footer_widgets( $input ) {
    $valid = array(
        '0' => '0',
		'1' => '1',
		'2' => '2',
		'3' => '3',
		'4' => '4',
		'5' => '5'
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '3';
    }
}

/**
 * Sanitize blog excerpt
 * @since 1.0.8
 */
function generate_sanitize_blog_excerpt( $input ) {
    $valid = array(
        'full' => __( 'Show full post', 'generate' ),
		'excerpt' => __( 'Show excerpt', 'generate' )
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return 'full';
    }
}

/**
 * Sanitize colors
 * Allow blank value
 * @since 1.2.9.6
 */
function generate_sanitize_hex_color( $color ) {
    if ( '' === $color )
        return '';
 
    // 3 or 6 hex digits, or the empty string.
    if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
        return $color;
 
    return '';
}